<?php 

/**
 * SharedController Controller
 * @category  Controller / Model
 */
class SharedController extends BaseController{
	
	/**
     * rekam_medis_nama_pasien_option_list Model Action
     * @return array
     */
	function rekam_medis_nama_pasien_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_pasien AS value,nama_pasien AS label FROM pasien ORDER BY nama_pasien";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * rekam_medis_dokter_option_list Model Action
     * @return array
     */
	function rekam_medis_dokter_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama AS value,nama AS label FROM dokter ORDER BY nama";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * rekam_medis_obat_option_list Model Action
     * @return array
     */
	function rekam_medis_obat_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_obat AS value,nama_obat AS label FROM obat";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * rekam_medis_ruang_option_list Model Action
     * @return array
     */
	function rekam_medis_ruang_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT  DISTINCT nama_ruang AS value,nama_ruang AS label FROM ruang";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * pengguna_user_role_id_option_list Model Action
     * @return array
     */
	function pengguna_user_role_id_option_list(){
		$db = $this->GetModel();
		$sqltext = "SELECT role_id AS value, role_name AS label FROM roles";
		$queryparams = null;
		$arr = $db->rawQuery($sqltext, $queryparams);
		return $arr;
	}

	/**
     * pengguna_username_value_exist Model Action
     * @return array
     */
	function pengguna_username_value_exist($val){
		$db = $this->GetModel();
		$db->where("username", $val);
		$exist = $db->has("pengguna");
		return $exist;
	}

	/**
     * pengguna_email_value_exist Model Action
     * @return array
     */
	function pengguna_email_value_exist($val){
		$db = $this->GetModel();
		$db->where("email", $val);
		$exist = $db->has("pengguna");
		return $exist;
	}

	/**
     * getcount_rekammedis Model Action
     * @return Value
     */
	function getcount_rekammedis(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM rekam_medis";
		$queryparams = null;
		if (USER_ROLE_NAME == 'pasien') {
			$sqltext = "SELECT COUNT(*) AS num FROM rekam_medis WHERE id_pasien = :id_pasien";
			$queryparams = array('id_pasien' => USER_PASIEN_ID);
		} elseif (USER_ROLE_NAME == 'dokter') {
			$sqltext = "SELECT COUNT(*) AS num FROM rekam_medis WHERE id_dokter = :id_dokter";
			$queryparams = array('id_dokter' => USER_DOKTER_ID);
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_dokter Model Action
     * @return Value
     */
	function getcount_dokter(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM dokter";
		$queryparams = null;
		if (USER_ROLE_NAME == 'pasien') {
			$sqltext = "SELECT COUNT(DISTINCT id_dokter) AS num FROM rekam_medis WHERE id_pasien = :id_pasien";
			$queryparams = array('id_pasien' => USER_PASIEN_ID);
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_pasien Model Action
     * @return Value
     */
	function getcount_pasien(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM pasien";
		$queryparams = null;
		if (USER_ROLE_NAME == 'pasien') {
			return 1;
		} elseif (USER_ROLE_NAME == 'dokter') {
			$sqltext = "SELECT COUNT(DISTINCT id_pasien) AS num FROM rekam_medis WHERE id_dokter = :id_dokter";
			$queryparams = array('id_dokter' => USER_DOKTER_ID);
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

	/**
     * getcount_pengguna Model Action
     * @return Value
     */
	function getcount_pengguna(){
		$db = $this->GetModel();
		$sqltext = "SELECT COUNT(*) AS num FROM pengguna";
		$queryparams = null;
		if (USER_ROLE_NAME == 'pasien' || USER_ROLE_NAME == 'dokter') {
			return 0;
		}
		$val = $db->rawQueryValue($sqltext, $queryparams);
		if(is_array($val)){
			return $val[0];
		}
		return $val;
	}

}
