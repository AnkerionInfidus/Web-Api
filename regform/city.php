<?php
	require_once("php/model/MyDatabase.php");	
	$myDB = new MyDatabase();
	if (isset($_POST['country']) && filter_var($_POST['country'], FILTER_VALIDATE_INT) && intval($_POST['country']) > 0 ) {
		$cities = $myDB->getCityList($_POST['country']);
		foreach ($cities as $id => $value) {
			echo "<option value='$id'>$value</option>\n";			
		}
	}
?>