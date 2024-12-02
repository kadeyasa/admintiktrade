<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$title = "ADMIN - TURNOVER DETAIL ";
include('header.php');
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}

$draw = $_POST['draw'];
$start = (int)$_POST['start'];
$length = (int)$_POST['length'];
$search_value = $_POST['search']['value'];

$datas = getuseromset();

foreach ($datas as &$data) { 
    $data['actions'] = '
        <a class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro ease-soft-in text-xs hover:scale-102 active:shadow-soft-xs tracking-tight-soft border-fuchsia-500 text-fuchsia-500 hover:border-fuchsia-500 hover:bg-transparent hover:text-fuchsia-500 hover:opacity-75 hover:shadow-none active:bg-fuchsia-500 active:text-white active:hover:bg-transparent active:hover:text-fuchsia-500" data-id="' . $data['upline_id'] . '" href="?upline='.$data['upline_id'].'">View</a>
    ';
}

$response = [
    "draw" => $draw,
    "recordsTotal" => $records_total,
    "recordsFiltered" => $records_filtered,
    "data" => $datas
];

echo json_encode($response);
