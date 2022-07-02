<?php

	require_once("php/model/MyDatabase.php");
	require_once("php/model/validation.php");
	
	session_start();
	if ($_SESSION['en']) {
		require_once("php/language/en.php");
	} else {
		require_once("php/language/ru.php");
	}
	session_write_close();

	$oMyDB = new MyDatabase();					
	$aFormData = $_POST['AuthorizationForm'];	
	$aResult = array("error" => "", 'id' => -1);
	if ( !empty($aFormData['email']) && filter_var($aFormData['email'], FILTER_VALIDATE_EMAIL)) {
		$aFormData['email'] = Validation::cleanString($aFormData['email']);
		$data = $oMyDB->getAuthData($aFormData['email']);
		if ($data != NULL) {
			$password = md5(sha1($aFormData['password']));
			if (!strcmp($password, $data['password'])) {
				session_start();
				$_SESSION['id'] = $data['id'];
				$aResult['id'] = $data['id'];	
				session_write_close();
			} else {
				$aResult['error'] = $lang['auth']['error']['password'];	
			}
		} else {
			$aResult['error'] = $lang['auth']['error']['not_found_email'];			
		}
	} else {
		$aResult['error'] = $lang['auth']['error']['wrong_email'];
	}
	echo json_encode($aResult);

?>