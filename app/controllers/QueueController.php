<?php
/**
 * Queue Management and CommandCenter Controller
 * @category  Controller
 */
class QueueController extends SecureController {
    
    /**
     * Atomically shift queue entries from one doctor to another
     * Usage: POST /queue/reassign_queue with from_doctor_id, to_doctor_id, and batch_size
     */
    /**
     * Reassign a batch of waiting patients from one doctor to another (Queue Load Balancer Logic)
     */
    public function reassignQueueBatch($from_doctor_id, $to_doctor_id, $limit_count) {
        $db = $this->GetModel();
        
        // Find oldest waiting queue items for $from_doctor_id
        $db->where('id_dokter', $from_doctor_id);
        $db->where('status_antrean', 'waiting');
        $db->orderBy('id_medis', 'ASC');
        $queue_items = $db->get('rekam_medis', $limit_count, 'id_medis');
        
        if (empty($queue_items)) {
            return 0; // 0 patients affected
        }
        
        $item_ids = array_column($queue_items, 'id_medis');
        
        // Atomically reassign them to $to_doctor_id
        $db->where('id_medis', $item_ids, 'in');
        $bool = $db->update('rekam_medis', array('id_dokter' => $to_doctor_id));
        
        if ($bool) {
            return count($item_ids);
        } else {
            throw new Exception("Database update failed: " . $db->getLastError());
        }
    }

    function reassign_queue() {
        // Only super_admin can manage queues
        if (USER_ROLE_NAME !== 'super_admin') {
            return render_error("Unauthorized access", 403);
        }
        $from_doctor_id = $this->post->from_doctor_id ?? $this->request->from_doctor_id;
        $to_doctor_id = $this->post->to_doctor_id ?? $this->request->to_doctor_id;
        $batch_size = intval(($this->post->batch_size ?? $this->request->batch_size) ?: 5);
        
        if (empty($from_doctor_id) || empty($to_doctor_id)) {
            return render_error("Parameter from_doctor_id dan to_doctor_id wajib diisi.", 400);
        }
        
        try {
            $affected = $this->reassignQueueBatch($from_doctor_id, $to_doctor_id, $batch_size);
            return render_json(array(
                'status' => 'success',
                'message' => "Berhasil memindahkan " . $affected . " antrean.",
                'affected_rows' => $affected
            ));
        } catch (Exception $e) {
            return render_error("Gagal memperbarui antrean: " . $e->getMessage(), 500);
        }
    }
    
    /**
     * Compile and return Command Center analytics and warning metrics
     * GET /queue/metrics
     */
    function metrics() {
        $db = $this->GetModel();
        
        // Fetch active queue list for today, sorted by Triage Level (emergency, urgent, routine)
        $db->join("pasien", "rekam_medis.id_pasien = pasien.id_pasien", "LEFT");
        $db->join("dokter", "rekam_medis.id_dokter = dokter.id_dokter", "LEFT");
        $db->join("ruang", "rekam_medis.id_ruang = ruang.id_ruang", "LEFT");
        
        $db->where("rekam_medis.status_antrean", array("waiting", "processing"), "in");
        $db->orderBy("FIELD(triage_level, 'emergency', 'urgent', 'routine')", "ASC");
        $db->orderBy("id_medis", "ASC");
        
        $active_queue = $db->get("rekam_medis", 50, array(
            "rekam_medis.id_medis",
            "rekam_medis.tanggal_periksa",
            "pasien.nama_pasien AS nama_pasien",
            "rekam_medis.keluhan",
            "dokter.nama AS dokter_nama",
            "rekam_medis.id_dokter",
            "rekam_medis.triage_level",
            "rekam_medis.status_antrean",
            "ruang.nama_ruang AS ruang_nama"
        ));
        
        // Compile Doctor Queue metrics to check for overload (> 8 waiting)
        $sql_doctors = "SELECT d.id_dokter, d.nama, COUNT(rm.id_medis) AS waiting_count 
                        FROM dokter d 
                        LEFT JOIN rekam_medis rm ON d.id_dokter = rm.id_dokter AND rm.status_antrean = 'waiting'
                        GROUP BY d.id_dokter";
        $doctor_queues = $db->rawQuery($sql_doctors);
        
        // Fetch overloaded doctor threshold from system configurations
        $db->where("config_key", "max_queue_per_doctor");
        $max_queue = intval($db->getValue("system_configs", "config_value") ?: 8);
        
        $overloaded_doctors = array();
        foreach ($doctor_queues as $dq) {
            if ($dq['waiting_count'] > $max_queue) {
                $overloaded_doctors[] = $dq;
            }
        }
        
        // Compile EWS Medicine stock warnings
        $db->where("stok <= min_safety_threshold");
        $critical_medicines = $db->get("obat", 20, "id_obat, nama_obat, stok, min_safety_threshold");
        
        // Compile EWS Room Capacity Utilization
        $rooms = $db->get("ruang", null, "id_ruang, nama_ruang, max_capacity, current_occupancy");
        
        $total_capacity = 0;
        $total_occupancy = 0;
        $critical_rooms = array();
        
        foreach ($rooms as $r) {
            $total_capacity += $r['max_capacity'];
            $total_occupancy += $r['current_occupancy'];
            
            $utilization = $r['max_capacity'] > 0 ? ($r['current_occupancy'] / $r['max_capacity']) : 0;
            if ($utilization >= 0.90) {
                $r['utilization'] = round($utilization * 100, 1);
                $critical_rooms[] = $r;
            }
        }
        
        $overall_utilization = $total_capacity > 0 ? round(($total_occupancy / $total_capacity) * 100, 1) : 0;
        
        // General widget counts
        $total_rm = $db->getValue("rekam_medis", "COUNT(*)");
        $total_doc = $db->getValue("dokter", "COUNT(*)");
        $total_pasien = $db->getValue("pasien", "COUNT(*)");
        $total_pengguna = $db->getValue("pengguna", "COUNT(*)");
        
        return render_json(array(
            'active_queue' => $active_queue,
            'all_doctors' => $doctor_queues,
            'overloaded_doctors' => $overloaded_doctors,
            'critical_medicines' => $critical_medicines,
            'room_metrics' => array(
                'overall_utilization' => $overall_utilization,
                'critical_rooms' => $critical_rooms,
                'all_rooms' => $rooms
            ),
            'counts' => array(
                'rekam_medis' => $total_rm,
                'dokter' => $total_doc,
                'pasien' => $total_pasien,
                'pengguna' => $total_pengguna
            )
        ));
    }
}
