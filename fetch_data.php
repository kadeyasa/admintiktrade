<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - BONUS SETUP ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
if(isset($_GET['parent_id'])){
    $parent_id = $_GET['parent_id'];
}else{
    $parent_id = '';
}
getgeonology($parent_id);