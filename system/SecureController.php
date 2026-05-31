<?php
/**
* Extends to Application Base Controller.
* Page Controllers which need page authentication and authorization can extend to this class 
*/
class SecureController extends BaseController{
	function __construct(){
		parent::__construct();
		// Page actions which do not require authentication.
		$exclude_pages = array();
		$url = Router :: $page_url;
		$url = str_ireplace("/index", "/list", $url);
		$acl = new ACL;
		if(!empty($url)){
			$url_segment =$url_segment = explode("/" , rtrim($url , "/")) ;
			$controller = strtolower(!empty($url_segment[0]) ? $url_segment[0] : null);
			$action = strtolower((!empty($url_segment[1]) ? $url_segment[1] : "list"));
			$page = "$controller/$action";
			if(!in_array($page , $exclude_pages)){
				if($this->authenticate_user()){
					
					$page = Router::$page_url; //current page path
					$this->status = ACL::GetPageAccess($page); 

					if ($this->status == AUTHORIZED && (is_post_request() || $action == 'delete')) {
						$this->log_user_activity($controller, $action);
					}
				}
				else{
					$this->status = UNAUTHORIZED;
				}
			}
		}
	}

	/**
	 * Log user audit trail activity
	 */
	private function log_user_activity($controller, $action) {
		$critical_pages = array('rekam_medis', 'obat', 'pasien');
		$critical_actions = array('add', 'edit', 'editfield', 'delete');
		
		if (in_array($controller, $critical_pages) && in_array($action, $critical_actions)) {
			$db = $this->GetModel();
			
			$user_id = USER_ID;
			$username = USER_NAME ?: 'Guest';
			$ip_address = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
			
			$action_type = strtoupper($action . '_' . $controller);
			$page_id = Router::$page_id;
			
			$description = "User " . $username . " melakukan aksi " . strtoupper($action) . " pada " . ucfirst($controller);
			if ($page_id) {
				$description .= " dengan ID " . $page_id;
			}
			
			if ($controller == 'obat') {
				if ($action == 'add' && isset($_POST['nama_obat'])) {
					$description = "Admin " . $username . " menambahkan data obat: " . $_POST['nama_obat'];
				} elseif ($action == 'edit' && isset($_POST['nama_obat'])) {
					$description = "Admin " . $username . " mengubah data obat: " . $_POST['nama_obat'];
				} elseif ($action == 'delete') {
					$description = "Admin " . $username . " menghapus data obat dengan ID: " . $page_id;
				}
			} elseif ($controller == 'pasien') {
				if ($action == 'add' && isset($_POST['nama_pasien'])) {
					$description = "Admin " . $username . " menambahkan data pasien: " . $_POST['nama_pasien'];
				} elseif ($action == 'edit' && isset($_POST['nama_pasien'])) {
					$description = "Admin " . $username . " mengubah data pasien: " . $_POST['nama_pasien'];
				} elseif ($action == 'delete') {
					$description = "Admin " . $username . " menghapus data pasien dengan ID: " . $page_id;
				}
			} elseif ($controller == 'rekam_medis') {
				if ($action == 'add') {
					$description = "Admin " . $username . " menambahkan rekam medis baru";
				} elseif ($action == 'edit') {
					$description = "Admin " . $username . " mengubah rekam medis ID: " . $page_id;
				} elseif ($action == 'delete') {
					$description = "Admin " . $username . " menghapus rekam medis ID: " . $page_id;
				}
			}
			
			$log_data = array(
				'user_id' => $user_id,
				'action' => $action_type,
				'description' => $description,
				'ip_address' => $ip_address,
				'created_at' => datetime_now()
			);
			
			$db->insert('activity_logs', $log_data);
		}
	}

	/**
	 * Authenticate And Check User Page Access Eligibility
	 * @return  Redirect to Login Page Or Displays Error Message When user access control authorization Fails
	 */
	private function authenticate_user()
	{
		if (user_login_status() == false) {
			//check if user has a login cookie
			$session_key = get_cookie("login_session_key");
			if (!empty($session_key)) {
				$db = $this->GetModel();
				$db->where("login_session_key", hash_value($session_key));
				$user = $db->getOne("__tablename");
				if (!empty($user)) {
					set_session("user_data", $user);
				}
			}
		}
		return user_login_status();
	}
}