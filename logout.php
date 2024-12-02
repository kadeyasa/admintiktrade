<?php 
	session_start();
	unset($_SESSION['tokenadmin']);
	$_SESSION['tokenadmin']='';
	header('location:login.php');
