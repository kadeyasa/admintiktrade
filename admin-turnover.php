
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
if(isset($_GET['username'])){
    $check = getdata('member',$_GET['username'],'username');
    if(!$check){
        header('location:admin-turnover.php?error=notfound');
    }
    $upline = "'".$check['id']."'";
    $username=$_GET['username'];
}else{
    $upline=null;
    $username='';
}
if(isset($_GET['upline'])){
    $upline = $_GET['upline'];
}
if(!$upline){
    $results = getuseromset();
}else{
    $results = getuseromset($upline);
}
$start='';
$end ='';
?>
<body>
<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Member Turnover</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Member Turnover</h5>
				<br/>
                <?php 
                    if(isset($_GET['error'])){
                        ?>
                        <div class="alert alert-danger" role="alert">User Not Found!!!</div>
                        <?php
                    }
                ?>
                <form method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" aria-label="Recipient's username" aria-describedby="basic-addon2" name="username" value="<?php echo $username;?>">
                        
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Date Range" aria-label="Recipient's username" aria-describedby="basic-addon2" name="daterange" value="<?php echo $daterange;?>">
                        <div class="input-group-append">
                            <button class="input-group-text" id="basic-addon2">Cari</button>
                        </div>
                    </div>
                </form>
				<table width="100%;" class="table" style="font-size:12px;" id="table-turnover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Turnover</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i=0;
                            foreach($results as $row){
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['username'];?></td>
                                    <td align="right"><?php echo number_format($row['total_credit'],2);?></td>
                                    <td align="center">
                                        <a href="?upline=<?php echo $row['ref_code'];?>&start=<?php echo $start;?>&end=<?php echo $end;?>">View</a>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
  			</div>
		</div>
	</div>
    <?php include('footer.php');?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    
    <script type="text/javascript">

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>


</body>
</html>
