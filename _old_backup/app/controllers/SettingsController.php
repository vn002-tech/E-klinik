<?php
/**
 * System Settings & Backups Controller
 * @category  Controller
 */
class SettingsController extends SecureController {
    
    /**
     * Display settings dashboard with Tabbed views
     */
    function index() {
        if (USER_ROLE_NAME !== 'super_admin') {
            return $this->redirect("home");
        }
        
        $db = $this->GetModel();
        
        // Fetch current backups list
        $db->orderBy("id", "DESC");
        $backups = $db->get("backups");
        
        // Fetch threshold configurations
        $configs = $db->get("system_configs");
        $thresholds = array();
        foreach ($configs as $cfg) {
            $thresholds[$cfg['config_key']] = $cfg['config_value'];
        }
        
        // Fetch recent activity logs (limited to 100 entries)
        $db->orderBy("id", "DESC");
        $logs = $db->get("activity_logs", 100);
        
        $page_data = array(
            'backups' => $backups,
            'thresholds' => $thresholds,
            'logs' => $logs
        );
        
        return $this->render_view("settings/index.php", $page_data);
    }
    
    /**
     * Programmatically create a new SQL backup and zip it
     */
    function create_backup() {
        if (USER_ROLE_NAME !== 'super_admin') {
            return render_error("Unauthorized access", 403);
        }
        
        try {
            $db = $this->GetModel();
            
            // 1. Fetch tables
            $tables = array();
            $result = $db->rawQuery("SHOW TABLES");
            foreach ($result as $row) {
                $tables[] = current($row);
            }
            
            // 2. Generate SQL Dump
            $sql = "-- E-Klinik Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tables as $table) {
                $createTable = $db->rawQuery("SHOW CREATE TABLE `" . $table . "`");
                $sql .= "\n\nDROP TABLE IF EXISTS `" . $table . "`;\n";
                $sql .= $createTable[0]['Create Table'] . ";\n\n";
                
                $rows = $db->get($table);
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $keys = array_keys($row);
                        $escaped_values = array();
                        foreach ($row as $val) {
                            if ($val === null) {
                                $escaped_values[] = "NULL";
                            } else {
                                $escaped_values[] = "'" . addslashes($val) . "'";
                            }
                        }
                        $sql .= "INSERT INTO `" . $table . "` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $escaped_values) . ");\n";
                    }
                }
            }
            $sql .= "\n\nSET FOREIGN_KEY_CHECKS=1;\n";
            
            // 3. Define paths and directories
            $backup_dir = ROOT . "storage/app/backups/";
            if (!file_exists($backup_dir)) {
                mkdir($backup_dir, 0777, true);
            }
            
            $base_filename = "db_backup_" . date('Ymd_His');
            $sql_file = $backup_dir . $base_filename . ".sql";
            $zip_file = $backup_dir . $base_filename . ".zip";
            
            // Write temporary SQL file
            file_put_contents($sql_file, $sql);
            
            // 4. Create ZIP
            $zip = new ZipArchive();
            if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($sql_file, $base_filename . ".sql");
                $zip->close();
                // Delete temporary sql file
                unlink($sql_file);
            } else {
                throw new Exception("Gagal membuat berkas ZIP.");
            }
            
            $bytes = filesize($zip_file);
            if ($bytes >= 1048576) {
                $file_size = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $file_size = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $file_size = $bytes . ' Bytes';
            }
            
            // 5. Insert backup record
            $backup_data = array(
                'file_name' => $base_filename . ".zip",
                'file_size' => $file_size,
                'created_at' => datetime_now()
            );
            $db->insert("backups", $backup_data);
            
            return render_json(array(
                'status' => 'success',
                'message' => 'Cadangan database baru berhasil dibuat.',
                'file' => $base_filename . ".zip"
            ));
            
        } catch (Exception $e) {
            return render_error("Proses backup gagal: " . $e->getMessage(), 500);
        }
    }
    
    /**
     * Download backup ZIP file securely
     */
    function download_backup($id) {
        if (USER_ROLE_NAME !== 'super_admin') {
            return render_error("Unauthorized access", 403);
        }
        
        $db = $this->GetModel();
        $db->where("id", $id);
        $backup = $db->getOne("backups");
        
        if (!$backup) {
            return render_error("Berkas cadangan tidak ditemukan di database.", 404);
        }
        
        $filepath = ROOT . "storage/app/backups/" . $backup['file_name'];
        if (!file_exists($filepath)) {
            return render_error("Berkas cadangan fisik tidak ditemukan di server.", 404);
        }
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    
    /**
     * Delete backup file and record securely
     */
    function delete_backup($id) {
        if (USER_ROLE_NAME !== 'super_admin') {
            return render_error("Unauthorized access", 403);
        }
        
        $db = $this->GetModel();
        $db->where("id", $id);
        $backup = $db->getOne("backups");
        
        if (!$backup) {
            return render_error("Berkas cadangan tidak ditemukan.", 404);
        }
        
        $filepath = ROOT . "storage/app/backups/" . $backup['file_name'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        $db->where("id", $id);
        $db->delete("backups");
        
        return render_json(array(
            'status' => 'success',
            'message' => 'Cadangan database berhasil dihapus.'
        ));
    }
    
    /**
     * Save dynamic Alert thresholds to system_configs
     */
    function save_thresholds() {
        if (USER_ROLE_NAME !== 'super_admin') {
            return render_error("Unauthorized access", 403);
        }
        
        $min_stock = $this->post->min_safety_stock_obat ?? $this->request->min_safety_stock_obat;
        $max_queue = $this->post->max_queue_per_doctor ?? $this->request->max_queue_per_doctor;
        
        if (empty($min_stock) || empty($max_queue)) {
            return render_error("Ambang batas safety stock dan antrean dokter wajib diisi.", 400);
        }
        
        try {
            $db = $this->GetModel();
            
            // Save Stock threshold
            $db->where("config_key", "min_safety_stock_obat");
            $db->update("system_configs", array("config_value" => intval($min_stock)));
            
            // Sync with all items in obat table
            $db->update("obat", array("min_safety_threshold" => intval($min_stock)));
            
            // Save Queue threshold
            $db->where("config_key", "max_queue_per_doctor");
            $db->update("system_configs", array("config_value" => intval($max_queue)));
            
            return render_json(array(
                'status' => 'success',
                'message' => 'Ambang batas sistem berhasil disimpan.'
            ));
            
        } catch (Exception $e) {
            return render_error("Gagal menyimpan konfigurasi: " . $e->getMessage(), 500);
        }
    }
}
