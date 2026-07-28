<?php 
/**
 * Rekam_medis Page Controller
 * @category  Controller
 */
class Rekam_medisController extends SecureController{
	function __construct(){
		parent::__construct();
		$this->tablename = "rekam_medis";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		
		// Add JOINS to retrieve text representation of normalized relationships
		$db->join("pasien", "rekam_medis.id_pasien = pasien.id_pasien", "LEFT");
		$db->join("dokter", "rekam_medis.id_dokter = dokter.id_dokter", "LEFT");
		$db->join("obat", "rekam_medis.id_obat = obat.id_obat", "LEFT");
		$db->join("ruang", "rekam_medis.id_ruang = ruang.id_ruang", "LEFT");

		$fields = array(
			"rekam_medis.id_medis", 
			"rekam_medis.tanggal_periksa", 
			"pasien.nama_pasien AS nama_pasien", 
			"rekam_medis.keluhan", 
			"dokter.nama AS dokter", 
			"rekam_medis.diagnosa", 
			"obat.nama_obat AS obat", 
			"ruang.nama_ruang AS ruang",
			"rekam_medis.id_pasien",
			"rekam_medis.id_dokter",
			"rekam_medis.id_obat",
			"rekam_medis.id_ruang"
		);
		$pagination = $this->get_pagination(MAX_RECORD_COUNT); // get current pagination e.g array(page_number, page_limit)
		
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				rekam_medis.id_medis LIKE ? OR 
				rekam_medis.tanggal_periksa LIKE ? OR 
				pasien.nama_pasien LIKE ? OR 
				rekam_medis.keluhan LIKE ? OR 
				dokter.nama LIKE ? OR 
				rekam_medis.diagnosa LIKE ? OR 
				obat.nama_obat LIKE ? OR 
				ruang.nama_ruang LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "rekam_medis/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("rekam_medis.id_medis", ORDER_TYPE);
		}
		
		if($fieldname){
			// Map old filter columns to joined table names
			if ($fieldname == 'nama_pasien') $fieldname = 'pasien.nama_pasien';
			elseif ($fieldname == 'dokter') $fieldname = 'dokter.nama';
			elseif ($fieldname == 'obat') $fieldname = 'obat.nama_obat';
			elseif ($fieldname == 'ruang') $fieldname = 'ruang.nama_ruang';
			
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}

		// ROLE-BASED DATA SCOPING (PREVENT GLOBAL VIEW DATA LEAK)
		if (USER_ROLE_NAME == 'pasien') {
			$db->where("rekam_medis.id_pasien", USER_PASIEN_ID);
		} elseif (USER_ROLE_NAME == 'dokter') {
			$db->where("rekam_medis.id_dokter", USER_DOKTER_ID);
		}

		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Rekam Medis";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("rekam_medis/list.php", $data); //render the full page
	}
	/**
     * View record detail 
     * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		
		$db->join("pasien", "rekam_medis.id_pasien = pasien.id_pasien", "LEFT");
		$db->join("dokter", "rekam_medis.id_dokter = dokter.id_dokter", "LEFT");
		$db->join("obat", "rekam_medis.id_obat = obat.id_obat", "LEFT");
		$db->join("ruang", "rekam_medis.id_ruang = ruang.id_ruang", "LEFT");

		$fields = array(
			"rekam_medis.id_medis", 
			"rekam_medis.tanggal_periksa", 
			"pasien.nama_pasien AS nama_pasien", 
			"rekam_medis.keluhan", 
			"dokter.nama AS dokter", 
			"rekam_medis.diagnosa", 
			"obat.nama_obat AS obat", 
			"ruang.nama_ruang AS ruang",
			"rekam_medis.id_pasien",
			"rekam_medis.id_dokter"
		);
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("rekam_medis.id_medis", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			// IDOR PROTECTION & ROLE CHECK
			if (USER_ROLE_NAME == 'pasien' && $record['id_pasien'] !== USER_PASIEN_ID) {
				return $this->render_view("errors/forbidden.php", null, "info_layout.php");
			}
			if (USER_ROLE_NAME == 'dokter' && $record['id_dokter'] !== USER_DOKTER_ID) {
				return $this->render_view("errors/forbidden.php", null, "info_layout.php");
			}

			$page_title = $this->view->page_title = "View  Rekam Medis";
			$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
			$this->view->report_title = $page_title;
			$this->view->report_layout = "report_layout.php";
			$this->view->report_paper_size = "A4";
			$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("rekam_medis/view.php", $record);
	}
	/**
     * Insert new record to the database table
     * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields (matching client post attributes)
			$fields = $this->fields = array("tanggal_periksa","nama_pasien","keluhan","dokter","diagnosa","obat","ruang");
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_periksa' => 'required',
				'nama_pasien' => 'required',
				'keluhan' => 'required',
				'dokter' => 'required',
				'diagnosa' => 'required',
				'obat' => 'required',
				'ruang' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_periksa' => 'sanitize_string',
				'nama_pasien' => 'sanitize_string',
				'keluhan' => 'sanitize_string',
				'dokter' => 'sanitize_string',
				'diagnosa' => 'sanitize_string',
				'obat' => 'sanitize_string',
				'ruang' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			
			// Map raw text values to normalized relation IDs
			if (!empty($modeldata['nama_pasien'])) {
				$db->where('nama_pasien', $modeldata['nama_pasien']);
				$p = $db->getOne('pasien', 'id_pasien');
				$modeldata['id_pasien'] = $p ? $p['id_pasien'] : null;
			}
			if (!empty($modeldata['dokter'])) {
				$db->where('nama', $modeldata['dokter']);
				$d = $db->getOne('dokter', 'id_dokter');
				$modeldata['id_dokter'] = $d ? $d['id_dokter'] : null;
			}
			if (!empty($modeldata['obat'])) {
				$obat_val = is_array($modeldata['obat']) ? $modeldata['obat'][0] : $modeldata['obat'];
				$db->where('nama_obat', $obat_val);
				$o = $db->getOne('obat', 'id_obat');
				$modeldata['id_obat'] = $o ? $o['id_obat'] : null;
			}
			if (!empty($modeldata['ruang'])) {
				$db->where('nama_ruang', $modeldata['ruang']);
				$r = $db->getOne('ruang', 'id_ruang');
				$modeldata['id_ruang'] = $r ? $r['id_ruang'] : null;
			}
			
			// Clear deprecated columns
			unset($modeldata['nama_pasien']);
			unset($modeldata['dokter']);
			unset($modeldata['obat']);
			unset($modeldata['ruang']);

			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("rekam_medis");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Rekam Medis";
		$this->render_view("rekam_medis/add.php");
	}
	/**
     * Update table record with formdata
     * @param $rec_id (select record by table primary key)
     * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		
		// IDOR PROTECTION & OWNERSHIP CHECK FOR EXISTING RECORD
		$db->where("rekam_medis.id_medis", $rec_id);
		$existing = $db->getOne($tablename);
		if (!$existing) {
			$this->set_page_error("No record found");
			return $this->redirect("rekam_medis");
		}
		if (USER_ROLE_NAME == 'pasien' && $existing['id_pasien'] !== USER_PASIEN_ID) {
			return $this->render_view("errors/forbidden.php", null, "info_layout.php");
		}
		if (USER_ROLE_NAME == 'dokter' && $existing['id_dokter'] !== USER_DOKTER_ID) {
			return $this->render_view("errors/forbidden.php", null, "info_layout.php");
		}

		$fields = $this->fields = array("id_medis","tanggal_periksa","nama_pasien","keluhan","dokter","diagnosa","obat","ruang");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'tanggal_periksa' => 'required',
				'nama_pasien' => 'required',
				'keluhan' => 'required',
				'dokter' => 'required',
				'diagnosa' => 'required',
				'obat' => 'required',
				'ruang' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_periksa' => 'sanitize_string',
				'nama_pasien' => 'sanitize_string',
				'keluhan' => 'sanitize_string',
				'dokter' => 'sanitize_string',
				'diagnosa' => 'sanitize_string',
				'obat' => 'sanitize_string',
				'ruang' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			
			// Map raw text values to normalized relation IDs
			if (!empty($modeldata['nama_pasien'])) {
				$db->where('nama_pasien', $modeldata['nama_pasien']);
				$p = $db->getOne('pasien', 'id_pasien');
				$modeldata['id_pasien'] = $p ? $p['id_pasien'] : null;
			}
			if (!empty($modeldata['dokter'])) {
				$db->where('nama', $modeldata['dokter']);
				$d = $db->getOne('dokter', 'id_dokter');
				$modeldata['id_dokter'] = $d ? $d['id_dokter'] : null;
			}
			if (!empty($modeldata['obat'])) {
				$obat_val = is_array($modeldata['obat']) ? $modeldata['obat'][0] : $modeldata['obat'];
				$db->where('nama_obat', $obat_val);
				$o = $db->getOne('obat', 'id_obat');
				$modeldata['id_obat'] = $o ? $o['id_obat'] : null;
			}
			if (!empty($modeldata['ruang'])) {
				$db->where('nama_ruang', $modeldata['ruang']);
				$r = $db->getOne('ruang', 'id_ruang');
				$modeldata['id_ruang'] = $r ? $r['id_ruang'] : null;
			}
			
			// Clear deprecated columns
			unset($modeldata['nama_pasien']);
			unset($modeldata['dokter']);
			unset($modeldata['obat']);
			unset($modeldata['ruang']);

			if($this->validated()){
				$db->where("rekam_medis.id_medis", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					return $this->redirect("rekam_medis");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("rekam_medis");
					}
				}
			}
		}
		
		// For loading edit form view data (apply joins)
		$db->join("pasien", "rekam_medis.id_pasien = pasien.id_pasien", "LEFT");
		$db->join("dokter", "rekam_medis.id_dokter = dokter.id_dokter", "LEFT");
		$db->join("obat", "rekam_medis.id_obat = obat.id_obat", "LEFT");
		$db->join("ruang", "rekam_medis.id_ruang = ruang.id_ruang", "LEFT");
		
		$edit_fields = array(
			"rekam_medis.id_medis", 
			"rekam_medis.tanggal_periksa", 
			"pasien.nama_pasien AS nama_pasien", 
			"rekam_medis.keluhan", 
			"dokter.nama AS dokter", 
			"rekam_medis.diagnosa", 
			"obat.nama_obat AS obat", 
			"ruang.nama_ruang AS ruang"
		);
		$db->where("rekam_medis.id_medis", $rec_id);;
		$data = $db->getOne($tablename, $edit_fields);
		$page_title = $this->view->page_title = "Edit  Rekam Medis";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("rekam_medis/edit.php", $data);
	}
	/**
     * Update single field
     * @param $rec_id (select record by table primary key)
     * @param $formdata array() from $_POST
     * @return array
     */
	function editfield($rec_id = null, $formdata = null){
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		
		// IDOR PROTECTION & OWNERSHIP CHECK FOR EXISTING RECORD
		$db->where("rekam_medis.id_medis", $rec_id);
		$existing = $db->getOne($tablename);
		if (!$existing) {
			render_error("No record found");
			return;
		}
		if (USER_ROLE_NAME == 'pasien' && $existing['id_pasien'] !== USER_PASIEN_ID) {
			render_error("Unauthorized access");
			return;
		}
		if (USER_ROLE_NAME == 'dokter' && $existing['id_dokter'] !== USER_DOKTER_ID) {
			render_error("Unauthorized access");
			return;
		}

		$fields = $this->fields = array("id_medis","tanggal_periksa","nama_pasien","keluhan","dokter","diagnosa","obat","ruang");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'tanggal_periksa' => 'required',
				'nama_pasien' => 'required',
				'keluhan' => 'required',
				'dokter' => 'required',
				'diagnosa' => 'required',
				'obat' => 'required',
				'ruang' => 'required',
			);
			$this->sanitize_array = array(
				'tanggal_periksa' => 'sanitize_string',
				'nama_pasien' => 'sanitize_string',
				'keluhan' => 'sanitize_string',
				'dokter' => 'sanitize_string',
				'diagnosa' => 'sanitize_string',
				'obat' => 'sanitize_string',
				'ruang' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			
			// Map values to normalized fields if they are edited
			if (isset($modeldata['nama_pasien'])) {
				$db->where('nama_pasien', $modeldata['nama_pasien']);
				$p = $db->getOne('pasien', 'id_pasien');
				$modeldata['id_pasien'] = $p ? $p['id_pasien'] : null;
				unset($modeldata['nama_pasien']);
			}
			if (isset($modeldata['dokter'])) {
				$db->where('nama', $modeldata['dokter']);
				$d = $db->getOne('dokter', 'id_dokter');
				$modeldata['id_dokter'] = $d ? $d['id_dokter'] : null;
				unset($modeldata['dokter']);
			}
			if (isset($modeldata['obat'])) {
				$obat_val = is_array($modeldata['obat']) ? $modeldata['obat'][0] : $modeldata['obat'];
				$db->where('nama_obat', $obat_val);
				$o = $db->getOne('obat', 'id_obat');
				$modeldata['id_obat'] = $o ? $o['id_obat'] : null;
				unset($modeldata['obat']);
			}
			if (isset($modeldata['ruang'])) {
				$db->where('nama_ruang', $modeldata['ruang']);
				$r = $db->getOne('ruang', 'id_ruang');
				$modeldata['id_ruang'] = $r ? $r['id_ruang'] : null;
				unset($modeldata['ruang']);
			}

			if($this->validated()){
				$db->where("rekam_medis.id_medis", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if($bool && $numRows){
					return render_json(
						array(
							'num_rows' =>$numRows,
							'rec_id' =>$rec_id,
						)
					);
				}
				else{
					if($db->getLastError()){
						$page_error = $db->getLastError();
					}
					elseif(!$numRows){
						$page_error = "No record updated";
					}
					render_error($page_error);
				}
			}
			else{
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
     * Delete record from the database
     * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		
		// IDOR PROTECTION & OWNERSHIP CHECK FOR ALL RECORD IDS
		foreach ($arr_rec_id as $id) {
			$db->where("rekam_medis.id_medis", $id);
			$rec = $db->getOne($tablename);
			if ($rec) {
				if (USER_ROLE_NAME == 'pasien' && $rec['id_pasien'] !== USER_PASIEN_ID) {
					return $this->render_view("errors/forbidden.php", null, "info_layout.php");
				}
				if (USER_ROLE_NAME == 'dokter' && $rec['id_dokter'] !== USER_DOKTER_ID) {
					return $this->render_view("errors/forbidden.php", null, "info_layout.php");
				}
			}
		}

		$db->where("rekam_medis.id_medis", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("rekam_medis");
	}
}
