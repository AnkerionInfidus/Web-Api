<?php
require_once("php/model/MyDatabase.php");

session_start();

if (isset($_GET['exit'])) {
	unset($_SESSION['id']);
}

if (isset($_GET['en']) && $_GET['en']) {
	$_SESSION['en'] = true;
	include("php/language/en.php");		
} else if (isset($_GET['ru']) && $_GET['ru']){
	$_SESSION['en'] = false;
	include("php/language/ru.php");	
} else if (isset($_SESSION['en']) && $_SESSION['en']) { 
	include("php/language/en.php");
} else {
	$_SESSION['en'] = false;
	include("php/language/ru.php");	
}
session_write_close();

include("php/view/header.php");				
include("php/view/lang_switch.php");		
if (isset($_SESSION['id'])) {
	include("php/view/user.php");			
} else {
	include("php/view/registration_form.php");	
}
include("php/view/footer.php");			
?>