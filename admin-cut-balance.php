<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - CUT BALANCE ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
if(isset($_GET['daterange'])){
	$daterange = $_GET['daterange'];
	$daterange = str_replace(" ","",$daterange);
	$dateranges = explode("-",$daterange);
	$start_date = str_replace("/","-",$dateranges[0]);
	$end_date = str_replace("/","-",$dateranges[1]);
}else{
	$daterange = '';
	$start_date = date('Y-m-01');
	$end_date =date('Y-m-t');
}

$valid =true;
$per_page = 100;
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

$resultall = getdatacutbalances();

if(isset($_GET['keyword']) && $start_date!=''){
	$keyword = $_GET['keyword'];
	$results = getdatacutbalances(0,0,$keyword,$start_date,$end_date);
}else{
	$results = getdatacutbalances(0,0,'',$start_date,$end_date);
}
$totalpage = ceil(count($resultall)/$per_page);
include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Cutting Balances</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Cutting Balance Histories</h5>
                <div class="row">
                	<div class="col-md-12">
                        <form method="get">
							
							<input type="text" class="form-control" name="keyword" placeholder="Username">
							<br/>
                            <div class="input-group mb-3">
								
                                <input type="text" class="form-control" placeholder="Date Range" aria-label="Recipient's username" aria-describedby="basic-addon2" name="daterange" value="<?php echo $daterange;?>">
                                <div class="input-group-append">
                                    <button class="input-group-text" id="basic-addon2">Cari</button>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive custom-table-responsive">
                            <form method="get" id="myForm">
                            <input type="hidden" name="page" value="<?php echo $page;?>">
                            <table class="table custom-table" id="table-member">
                            	<thead>
            						<tr> 
              							<th scope="col">ID</th>
                        				<th scope="col">DATE</th>
                        				<th scope="col">DESCRIPTION</th>
                        				<th scope="col">AMOUNT</th>
            						</tr>
          						</thead>
                            	<tbody>
                        			<?php 
										$total =0;
                        				foreach($results as $row){
											$total=$total+$row['balance_out'];
                                        ?>
                                        <tr> 
              								<td scope="col" align="center">#<?php echo $row['id'];?></td>
                        					<td scope="col"><?php echo $row['created_at'];?></td>
                        					<td scope="col"><?php echo $row['description'];?></td>
                                            <td scope="col" align="right"><?php echo $row['balance_out'];?></td>
            							</tr>
                                   	<?php
                                        }
                        			?>
                            	</tbody>
                                <tfoot>
                                	<tr>
										<td colspan="3">Total</td>
                                    	<td align="right">
                                    		<?php echo number_format($total,6);?>
                                    	</td>
                                    </tr>
                                </tfoot>
                            </table>
                            </form>
                        </div>
                    </div>
                </div>
  			</div>
		</div>
	</div>
</body>
<?php
include('footer.php');
?>
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

