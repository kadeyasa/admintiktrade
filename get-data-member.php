<?php 
session_start();
include('api.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
if(isset($_POST['id'])){
	$id = $_POST['id'];
	$postdata = array(
    	'id'=>$id
    );
	$response = _postAPILogin('getdatamember',$_SESSION['tokenadmin'],$postdata);
	$data = $response;
}else{
	$data = array(
    	'error'=>1,
    	'message'=>'Invalid ID'
    );
}

echo json_encode($data);