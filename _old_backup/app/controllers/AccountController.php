<?php 
/**
 * Account Page Controller
 * @category  Controller
 */
class AccountController extends SecureController{
	function __construct(){
		parent::__construct(); 
		$this->tablename = "pengguna";
	}
	/**
		* Index Action
		* @return null
		*/
	function index(){
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID; //get current user id from session
		$db->where ("id_pengguna", $rec_id);
		$tablename = $this->tablename;
		$fields = array("id_pengguna", 
			"username", 
			"nama", 
			"jabatan", 
			"email", 
			"photo", 
			"user_role_id");
		$user = $db->getOne($tablename , $fields);
		if(!empty($user)){
			$page_title = $this->view->page_title = "My Account";
			$this->render_view("account/view.php", $user);
		}
		else{
			$this->set_page_error();
			$this->render_view("account/view.php");
		}
	}
	/**
     * Update user account record with formdata
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = USER_ID;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id_pengguna","username","nama","jabatan","photo","user_role_id");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$this->rules_array = array(
				'username' => 'required',
				'nama' => 'required',
				'jabatan' => 'required',
				'photo' => 'required',
				'user_role_id' => 'required',
			);
			$this->sanitize_array = array(
				'username' => 'sanitize_string',
				'nama' => 'sanitize_string',
				'jabatan' => 'sanitize_string',
				'photo' => 'sanitize_string',
				'user_role_id' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			//Check if Duplicate Record Already Exit In The Database
			if(isset($modeldata['username'])){
				$db->where("username", $modeldata['username'])->where("id_pengguna", $rec_id, "!=");
				if($db->has($tablename)){
					$this->view->page_error[] = $modeldata['username']." Already exist!";
				}
			} 
			if($this->validated()){
				$db->where("pengguna.id_pengguna", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					$db->where ("id_pengguna", $rec_id);
					$user = $db->getOne($tablename , "*");
					set_session("user_data", $user);// update session with new user data
					return $this->redirect("account");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$this->set_flash_msg("No record updated", "warning");
						return	$this->redirect("account");
					}
				}
			}
		}
		$db->where("pengguna.id_pengguna", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "My Account";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("account/edit.php", $data);
	}
	/**
     * Change account email
     * @return BaseView
     */
	function change_email($formdata = null){
		if($formdata){
			$email = trim($formdata['email']);
			$db = $this->GetModel();
			$rec_id = $this->rec_id = USER_ID; //get current user id from session
			$tablename = $this->tablename;
			$db->where ("id_pengguna", $rec_id);
			$result = $db->update($tablename, array('email' => $email ));
			if($result){
				$this->set_flash_msg("Email address changed successfully", "success");
				$this->redirect("account");
			}
			else{
				$this->set_page_error("Email not changed");
			}
		}
		return $this->render_view("account/change_email.php");
	}
	/**
     * Account Settings Action
     * @return BaseView
     */
	function settings($formdata = null){
		$db = $this->GetModel();
		$user_id = USER_ID;
		if ($formdata) {
			$postdata = $this->format_request_data($formdata);
			$notif_email = isset($postdata['notif_email']) ? 1 : 0;
			$notif_whatsapp = isset($postdata['notif_whatsapp']) ? 1 : 0;
			$two_factor_enabled = isset($postdata['two_factor_enabled']) ? 1 : 0;
			$update_data = array(
				'notif_email' => $notif_email,
				'notif_whatsapp' => $notif_whatsapp,
				'two_factor_enabled' => $two_factor_enabled
			);
			if (!empty($_FILES['verification_document']['name'])) {
				$uploader = new Uploader;
				$upload_settings = array(
					"title" => "{{random}}",
					"extensions" => ".jpg,.png,.jpeg,.pdf",
					"limit" => "1",
					"filesize" => "3",
					"returnfullpath" => false,
					"filenameprefix" => "",
					"uploadDir" => "uploads/files/"
				);
				$upload_data = $uploader->upload($_FILES['verification_document'], $upload_settings);
				if (!$upload_data['hasErrors'] && $upload_data['isComplete']) {
					$update_data['verification_document'] = $upload_data['data']['files'][0];
					$update_data['is_verified'] = 'pending';
				}
			}
			if (!empty($postdata['current_password']) && !empty($postdata['new_password'])) {
				$db->where('id_pengguna', $user_id);
				$u = $db->getOne('pengguna', 'password');
				if (password_verify($postdata['current_password'], $u['password'])) {
					if ($postdata['new_password'] === $postdata['confirm_password']) {
						$new_hash = password_hash($postdata['new_password'], PASSWORD_DEFAULT);
						$db->where('id_pengguna', $user_id);
						$db->update('pengguna', array('password' => $new_hash));
						$this->set_flash_msg("Password dan pengaturan berhasil diperbarui", "success");
					} else {
						$this->set_flash_msg("Konfirmasi password baru tidak cocok", "danger");
						return $this->redirect("account/settings");
					}
				} else {
					$this->set_flash_msg("Password lama Anda salah", "danger");
					return $this->redirect("account/settings");
				}
			}
			$db->where('user_id', $user_id);
			$db->update('user_settings', $update_data);
			$this->set_flash_msg("Pengaturan akun berhasil disimpan", "success");
			return $this->redirect("account/settings");
		}
		$db->where('user_id', $user_id);
		$settings = $db->getOne('user_settings');
		if (!$settings) {
			$db->insert('user_settings', array(
				'user_id' => $user_id,
				'notif_email' => 1,
				'notif_whatsapp' => 1,
				'two_factor_enabled' => 0,
				'is_verified' => 'unverified'
			));
			$db->where('user_id', $user_id);
			$settings = $db->getOne('user_settings');
		}
		$db->where('id_pengguna', $user_id);
		$user = $db->getOne('pengguna', array('id_pengguna', 'username', 'nama', 'email', 'photo', 'user_role_id'));
		$view_data = array(
			'user' => $user,
			'settings' => $settings
		);
		$this->view->page_title = "Pengaturan Akun";
		return $this->render_view("account/settings.php", $view_data);
	}
}
