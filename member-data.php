<?php 
// Construct POST data array
$search = array();
if(isset($_GET['idsearch'])){
	$idsearch = $_GET['idsearch'];
	
}else{
	$idsearch = '';
}

if(isset($_GET['namesearch'])){
	$namesearch = $_GET['namesearch'];
	
}else{
	$namesearch = '';
}

if(isset($_GET['emailsearch'])){
	$emailsearch = $_GET['emailsearch'];
	
}else{
	$emailsearch = '';
}

$per_page = 50;
if(isset($_GET['page'])){
	$page = $_GET['page'];
}else{
	$page = 0;
}
if($page>0){
	$start=($page-1)*$per_page;
}else{
	$start=$page;
}
if(isset($_GET['shortby'])){
	$sortby = $_GET['shortby'];
	$sort = $_GET['short'];
}else{
	$sortby = 'id';
	$sort = 'asc';
}
$_postdata = array(
    	"search" => $search,
		'sortby'=>$sortby,
		'emailsearch'=>$emailsearch,
		'namesearch'=>$namesearch,
		'search'=>$idsearch,
		'sort'=>$sort,
		'leg'=>$per_page,
		'start'=>$start
);
$respone = _postAPIlogin('resultallmember',$_SESSION['tokenadmin'],$_postdata);
return $respone;