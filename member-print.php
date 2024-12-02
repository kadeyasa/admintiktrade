<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require 'vendor/autoload.php';
use Dompdf\Dompdf;

error_reporting(E_ALL);
$title = "ADMIN - MEMBER ENERGY ";
session_start();
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
include('api.php');
include('connection.php');
include('function.php');

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$getdatarewards = getmemberdataactive($start_date,$end_date);

$type = $_GET['type'];

$total = 0;

echo '<center><b>Data Member '.$start_date.' to '.$end_date.'</b></center>';
echo '<br/>';
echo "<table width='100%;' cellpadding='0' cellspacing='0'>";
        echo "<tr>";
         echo '<td style="border:1px solid #000;" width="100" align="center">NO</td>';
        echo '<td style="border:1px solid #000;" width="100" align="center">REG DATE</td>';
        echo '<td style="border:1px solid #000;" width="200" align="center">USERNAME</td>';
        echo '<td style="border:1px solid #000;" width="200" align="center">STATUS MEMBER</td>';
        
    echo "</tr>";
    $no = 0;
    foreach($getdatarewards as $row){
        $date = new DateTime($row['created_date']);
        $formattedDate = $date->format('Y-m-d H:i:s');
        if($row['member_type']==0){
            $statusmember ='Free';
        }else{
            $statusmember='Pro';
        }
        $no++;
        echo "<tr>";
            echo '<td style="border:1px solid #000;" width="100" align="center">'.$no.'</td>';
            echo '<td style="border:1px solid #000;" width="100" align="center">'.$formattedDate.'</td>';
            echo '<td style="border:1px solid #000;" width="200" align="center">'.$row['username'].'</td>';
            echo '<td style="border:1px solid #000;" width="200" align="right">'.$statusmember.'</td>';
        echo "</tr>";
    }
echo "</table>";

if($type=='print'){
    echo '<script type="text/javascript">window.print();</script>';
}else if($type=='pdf'){
    $dompdf = new Dompdf();
    $dompdf->loadHtml(ob_get_clean());
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream();
}