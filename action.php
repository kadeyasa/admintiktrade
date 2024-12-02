<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
	
include('api.php');

$data = $_POST['info']; // Get the 'data' object sent via AJAX
$action = $_POST['action']; // Get the 'action' parameter
$method = $_POST['method']; // Get the 'method' parameter

//echo json_encode($data);
	
if(isset($action)){
	if($method=='post'){
    	echo json_encode(_postAPIlogin($action,$_SESSION['tokenadmin'],$data));
    }   	
}