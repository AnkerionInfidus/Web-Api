<?php
	

	require_once("php/model/validation.php"); 	
	require_once("php/model/MyDatabase.php");

	session_start();
	if ($_SESSION['en']) {
		require_once("php/language/en.php");
	} else {
		require_once("php/language/ru.php");
	}
	session_write_close();
	
 	$sDirectory = "uploads/img/";	/
	$sDefaultImage = "no_photo.png";
	$formData = $_POST['RegistrationForm'];
	$myDB = new MyDatabase();		
	$validForm = null; 				
	$result = array('error' => array(), 'email' => "", 'id' => -1);	 
	
	if (!empty($formData['other_country'])) {
		$formData['other_country'] = Validation::cleanString($formData['other_country']);
		$formData['country'] = $myDB->addCountry($formData['other_country']);
	}
	
	if (!empty($formData['other_city'])) {
		$formData['other_city'] = Validation::cleanString($formData['other_city']);
		$formData['city'] = $myDB->addCity($formData['other_city'], $formData['country']);
	}

	$validForm = new Validation($formData);
	
	if ($validForm->isError()) {
		
		$arr = array('Name', 'Email', 'Password', 'Reppassword', 'Gender', 'Country', 'City', 'Birth_day', 'Birth_month', 'Birth_year');
		foreach($arr as $v) {
			$meth = "is".$v;
			$result['error'][lcfirst($v)] = $validForm->$meth();
		}
	} else {
		
		$res = $validForm->getResult();
	
		if ($myDB->getEmail($res['email'])) {
			$result['email'] = $lang["error"]["same_email"]; 	
			
			if(isset($_FILES["photo"]['name']) && preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['photo']['name'])) {
				$sFileName = time();
			 	if(preg_match('/[.](GIF)|(gif)$/', $_FILES["photo"]['name'])) {
			    	$sExt = ".gif";
			    }
			    if(preg_match('/[.](PNG)|(png)$/', $_FILES["photo"]['name'])) {
			    	$sExt = ".png";
			    }  
			    if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $_FILES["photo"]['name'])) {
			    	$sExt = ".jpg"; 
			    }
			    move_uploaded_file($_FILES['photo']['tmp_name'], $sDirectory.$sFileName.$sExt);
			    $res['photo'] = $sDirectory.$sFileName.$sExt;
			} else {
			    $res['photo'] = $sDirectory.$sDefaultImage;
			}
			
			$myDB->insert($res);
			
			$result['id'] = $myDB->getId($res['email']);
	
			session_start();
			$_SESSION['id'] = $result['id'];
			session_write_close();
		}
	}

	echo json_encode($result); 
?>