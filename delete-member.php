<?php 
	session_start();
	include('api.php');
	if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
		header('location:login.php');
	}
	if(isset($_POST['id'])){
    	$_postdata = array(
        	'id'=>$_POST['id']
        );
    	$respone = _postAPIlogin('deletemember',$_SESSION['tokenadmin'],$_postdata);
    	$data = array(
        	'error'=>0,
        	'message'=>$respone['message']
        );
    	echo json_encode($data);
    }else{
    	$data = array(
        	'error'=>1,
        	'message'=>'Invalid ID'
        );
    	echo json_encode($data);
    }
?>