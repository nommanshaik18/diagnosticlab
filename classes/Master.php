<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_message(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `message_list` set {$data} ";
		}else{
			$sql = "UPDATE `message_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Your message has successfully sent.";
			else
				$resp['msg'] = "Message details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success' && !empty($id))
		$this->settings->set_flashdata('success',$resp['msg']);
		if($resp['status'] =='success' && empty($id))
		$this->settings->set_flashdata('pop_msg',$resp['msg']);
		return json_encode($resp);
	}
	function delete_message(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `message_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Message has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	
	function save_test(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `test_list` set {$data} ";
		}else{
			$sql = "UPDATE `test_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $rid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Test has successfully added.";
			else
				$resp['msg'] = "Test details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
// 	function save_patient()
// {
//     extract($_POST);
//     $data = "";
//     foreach ($_POST as $k => $v) {
//         if (!in_array($k, array('id'))) {
//             if (!is_numeric($v))
//                 $v = $this->conn->real_escape_string($v);
//             if (!empty($data)) $data .= ",";
//             $data .= " `{$k}`='{$v}' ";
//         }
//     }
//     if (empty($id)) {
//         $sql = "INSERT INTO `patients` SET {$data} ";
//     } else {
//         $sql = "UPDATE `patients` SET {$data} WHERE id = '{$id}' ";
//     }
//     $save = $this->conn->query($sql);
// 	$resp['err'] = $this->conn->error . " [{$sql}]";
//     if ($save) {
//         $rid = !empty($id) ? $id : $this->conn->insert_id;
//         $resp['id'] = $rid;
//         $resp['status'] = 'success';
//         if (empty($id))
//             $resp['msg'] = "Patient registered successfully.";
//         else
//             $resp['msg'] = "Patient details have been updated successfully.";
//     } else {
//         $resp['status'] = 'failed';
//         $resp['msg'] = "An error occurred.";
//         $resp['err'] = $this->conn->error . " [{$sql}]";
//     }
//     if ($resp['status'] == 'success') {
//         $this->settings->set_flashdata('success', $resp['msg']);
//     } else {
//         $this->settings->set_flashdata('error', $resp['msg']);
//     }
//     return json_encode($resp);
// }
public function save_patient(){
	// if(!empty($_POST['password'])){
	// 	$_POST['password'] = md5($_POST['password']);
	// }else{
	// 	unset($_POST['password']);
	// }
	extract($_POST);
	$data = '';
	// $chk = $this->conn->query("SELECT * FROM `patients` where email ='{$email}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
	// if($chk > 0){
	// 	return 3;
	// 	exit;
	// }
	foreach($_POST as $k => $v){
		if(!in_array($k,array('id'))){
			if(!empty($data)) $data .=" , ";
			$data .= " {$k} = '{$v}' ";
		}
	}
	if(empty($id)){
		$qry = $this->conn->query("INSERT INTO patients set {$data}");
		if($qry){
			$id = $this->conn->insert_id;
			$resp['msg']="patients Successfully Registered.";
			$resp['status'] = 1;
		}else{
			$resp['status'] = 2;
			$resp['sql'] = "INSERT INTO patients set {$data}";
			$resp['err'] = $this->conn->error;
			return json_encode($resp);
		}

	}else{
		$qry = $this->conn->query("UPDATE patients set $data where id = {$id}");
		if($qry){
			$resp['msg']="patients Details successfully updated.";
			if($id == $this->settings->userdata('id')){
				foreach($_POST as $k => $v){
					if($k != 'id'){
						if(!empty($data)) $data .=" , ";
						$this->settings->set_userdata($k,$v);
					}
				}
				
			}
			$resp['status'] = 1;
		}else{
			$resp['status'] = 2;
		}
		
	}
	
	if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
		$fname = 'uploads/client-'.$id.'.png';
		$dir_path =base_app. $fname;
		$upload = $_FILES['img']['tmp_name'];
		$type = mime_content_type($upload);
		$allowed = array('image/png','image/jpeg');
		if(!in_array($type,$allowed)){
			$resp['msg'].=" But Image failed to upload due to invalid file type.";
		}else{
			$new_height = 200; 
			$new_width = 200; 
	
			list($width, $height) = getimagesize($upload);
			$t_image = imagecreatetruecolor($new_width, $new_height);
			imagealphablending( $t_image, false );
			imagesavealpha( $t_image, true );
			$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
			imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			if($gdImg){
					if(is_file($dir_path))
					unlink($dir_path);
					$uploaded_img = imagepng($t_image,$dir_path);
					imagedestroy($gdImg);
					imagedestroy($t_image);
					if(isset($uploaded_img)){
						$this->conn->query("UPDATE client_list set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
						if($id == $this->settings->userdata('id')){
								$this->settings->set_userdata('avatar',$fname);
						}
					}
			}else{
			$resp['msg'].=" But Image failed to upload due to unkown reason.";
			}
		}
		
	}
	if(isset($resp['msg']))
	$this->settings->set_flashdata('success',$resp['msg']);
	return  $resp['status'];
}
	

	// function save_patient(){
	// 	extract($_POST);
	// 	$data = "";
	// 	foreach($_POST as $k =>$v){
	// 		if(!in_array($k,array('id'))){
	// 			if(!is_numeric($v))
	// 				$v = $this->conn->real_escape_string($v);
	// 			if(!empty($data)) $data .=",";
	// 			$data .= " `{$k}`='{$v}' ";
				
	// 		}
	// 	}
	// 	echo "<script>console.log('Debug Objects: " . $data . "' );</script>";
	// }
	function delete_test(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `test_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Test has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_appointment(){
		if(empty($_POST['id'])){
			$prefix = date("Ym")."-";
			$code = sprintf("%'.04d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `appointment_list` where `code` = '{$prefix}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.04d",ceil($code)+ 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
			$_POST['client_id'] = $this->settings->userdata('id');
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id','test_ids')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `appointment_list` set {$data} ";
		}else{
			$sql = "UPDATE `appointment_list` set {$data} where id = '{$id}' ";
		}
	
		$save = $this->conn->query($sql);
		if($save){
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['aid'] = $aid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Appointment has successfully added.";
			else
				$resp['msg'] = "Appointment has been updated successfully.";
			if(isset($test_ids) && count($test_ids) > 0){
				$data = "";
				foreach($test_ids as $k=>$v){
					if(!empty($data)) $data .= ", ";
					$data .= "('{$aid}','{$v}')";
				}
				if(!empty($data)){
					$this->conn->query("DELETE * FROM `appointment_test_list` where `appointment_id` = $aid");
					$sql = "INSERT INTO `appointment_test_list` (`appointment_id`, `test_id`) VALUES {$data}";
					$save2 = $this->conn->query($sql);
					if(!$save2){
						$resp['status'] = 'failed';
						$resp['msg'] = 'An error occurred while saving the list of tests.';
						$resp['error'] = $this->conn->error;
						if(empty($id))
						$this->conn->query("DELETE * FROM `appointment_list` where id = '{$aid}'");
						return json_encode($resp);
					}
				}
			}

			if(isset($_FILES['prescription']) && !empty($_FILES['prescription']['tmp_name'])){
				$file = $_FILES['prescription']['tmp_name'];
				if(!is_dir(base_app."uploads/prescriptions"))
					mkdir((base_app."uploads/prescriptions"));
				$i = 0;
				while(true){
				$fname = ($i > 0 ? $i."_" : '').str_replace(" ","_",$_FILES['prescription']['name']);
					if(is_file(base_app."uploads/prescriptions/".$fname)){
						$i++;
					}else{
						break;
					}
				}
				$move = move_uploaded_file($_FILES['prescription']['tmp_name'],base_app."uploads/prescriptions/".$fname);
				if($move){
					$this->conn->query("UPDATE `appointment_list` set prescription_path = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$aid}'");
				}else{
					$resp['msg'] .= " But prescription file was failed to upload.";
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function update_appointment_status(){
		extract($_POST);
		if(isset($_FILES['report'])){
			$type = mime_content_type($_FILES['report']['tmp_name']);
			if($type != 'application/pdf'){
				$resp['status'] = 'failed';
				$resp['msg'] = 'Invalid File Type';
				$resp['error'] = $this->conn->error;
				return json_encode($resp);
			}
		}
		$update = $this->conn->query("UPDATE `appointment_list` set `status` = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Reservation status has been updated successfully.");
			$sql = "INSERT INTO history_list (`appointment_id`,`remarks`,`status`) VALUES ('{$id}','{$this->conn->real_escape_string($remarks)}','{$status}')";
			$this->conn->query($sql);
			$this->conn->query("DELETE FROM `history_list` where `status` > '{$status}' ");
			if(isset($_FILES['report'])){
				if(!is_dir(base_app."uploads/reports/"))
				mkdir(base_app."uploads/reports/");
				$get = $this->conn->query("SELECT * FROM appointment_list where id = '{$id}'");
				$res = $get->fetch_array();
				$code = $res['code'];
				$fname = $code.".pdf";
				if(is_file(base_app."uploads/reports/".$fname)){
					unlink(base_app."uploads/reports/".$fname);
				}
				move_uploaded_file($_FILES['report']['tmp_name'],base_app."uploads/reports/".$fname);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function delete_appointment(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `appointment_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"appointment has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_reservation(){
		if(empty($_POST['id'])){
			$prefix = date("Ym")."-";
			$code = sprintf("%'.04d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `reservation_list` where `code` = '{$prefix}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.04d",ceil($code)+ 1);
				}else{
					break;
				}
			}
			$_POST['code'] = $prefix.$code;
		}
		extract($_POST);
		$check = $this->conn->query("SELECT * FROM `reservation_list` where test_id = '{$test_id}' and ((date('{$check_in}') BETWEEN `check_in` and `check_out`) or (date('{$check_out}') BETWEEN `check_in` and `check_out`)) and `status` in (0,1) ")->num_rows;
		if($check > 0){
			$resp['status'] = "failed";
			$resp['msg'] = "Your Date of Reservation for this test complicates with other reservations.";
			return json_encode($resp);
		}
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `reservation_list` set {$data} ";
		}else{
			$sql = "UPDATE `reservation_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $rid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "test Reservation has successfully submitted.";
			else
				$resp['msg'] = "test Reservation details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_reservation(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `reservation_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Reservation Details has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_activity(){
		$_POST['description'] = htmlentities($_POST['description']);
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `activity_list` set {$data} ";
		}else{
			$sql = "UPDATE `activity_list` set {$data} where id = '{$id}' ";
		}
		$save = $this->conn->query($sql);
		if($save){
			$rid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $rid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Activity has successfully added.";
			else
				$resp['msg'] = "Activity details has been updated successfully.";
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				if(!is_dir(base_app.'uploads/activitys'))
				mkdir(base_app.'uploads/activitys');

				$fname = 'uploads/activitys/'.$rid.'.png';
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$resp['msg'].=" But Image failed to upload due to invalid file type.";
				}else{
					$new_height = 400; 
					$new_width = 600; 
			
					list($width, $height) = getimagesize($upload);
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$resp['msg'].=" But Image failed to upload due to unkown reason.";
					}
				}
				if(isset($uploaded_img)){
					$this->conn->query("UPDATE activity_list set `image_path` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$rid}' ");
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_activity(){
		extract($_POST);
		$del = $this->conn->query("UPDATE `activity_list` set delete_flag = 1 where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Activity has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_message':
		echo $Master->save_message();
	break;
	case 'delete_message':
		echo $Master->delete_message();
	break;
	case 'save_test':
		echo $Master->save_test();
	break;
	case 'delete_test':
		echo $Master->delete_test();
	break;
	case 'save_appointment':
		echo $Master->save_appointment();
	break;
	case 'delete_appointment':
		echo $Master->delete_appointment();
	break;
	case 'update_appointment_status':
		echo $Master->update_appointment_status();
	break;
	case 'save_reservation':
		echo $Master->save_reservation();
	break;
	case 'delete_reservation':
		echo $Master->delete_reservation();
	break;
	case 'save_activity':
		echo $Master->save_activity();
	break;
	case 'delete_activity':
		echo $Master->delete_activity();
	break;
	default:
		// echo $sysset->index();
		break;
}