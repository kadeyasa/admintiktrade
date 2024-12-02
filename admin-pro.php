<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - MEMBER ENERGY ";
session_start();
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
include('api.php');
include('connection.php');
include('function.php');
include('header.php');
$valid = true;

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
if(isset($_GET['keyword'])){
    $username = $_GET['keyword'];
    $keyword = $_GET['keyword'];
}else{
    $username ='';
    $keyword ='';
}

if(isset($_GET['daterange'])){
	$daterange = $_GET['daterange'];
	$daterange = str_replace(" ","",$daterange);
	$dateranges = explode("-",$daterange);
	$start_date = str_replace("/","-",$dateranges[0]);
	$end_date = str_replace("/","-",$dateranges[1]);
	$totalupgraded = getmemberupgraded($start_date,$end_date);
}else{
	$daterange = '';
	$start_date = date('Y-m-01');
	$end_date =date('Y-m-t');
	$totalupgraded = getmemberupgraded();
}

//daily
$start_today = date('Y-m-d 00:00:00');
$end_today = date('Y-m-d 23:59:59');
$totalupgraded_daily = getmemberupgraded($start_today,$end_today);

$start_month = date('Y-m-01 00:00:00');
$end_month = date('Y-m-t 23:59:59');
$totalupgraded_monthly = getmemberupgraded($start_month,$end_month);

$idsearch='username';
$rewards = getmemberpro();
$getdatarewards = getmemberpro($start,$per_page,$keyword,$start_date,$end_date);

//echo json_encode($totalupgraded);
//echo json_encode($rewards);
$totalpage = ceil(count($rewards)/$per_page);

?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Upgraded Member List</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Upgrade Member</h5>
				<br/>
				<div class="row">	
					<div class="col-lg-3">
						<div class="card text-bg-primary mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-person"></i>&nbsp;Total Member Upgrade</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($totalupgraded['total']);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-warning mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Total Amount</b></div>
  							<div class="card-body">
    							<p class="card-text">
									<?PHP if(!is_null($totalupgraded['totalcredit'])){?>
										$<?php echo number_format($totalupgraded['totalcredit'],2);?>
									<?php }else{ echo '$0.00';}?>
								</p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-danger mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Amount Today Upgrade</b></div>
  							<div class="card-body">
    							<p class="card-text">
									<?PHP if(!is_null($totalupgraded_daily['totalcredit'])){?>
										$<?php echo number_format($totalupgraded_daily['totalcredit'],2);?>
									<?php }else{ echo '$0.00';}?>
								</p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-default mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Amount Monthly Upgrade</b></div>
  							<div class="card-body">
    							<p class="card-text">
									<?PHP if(!is_null($totalupgraded_monthly['totalcredit'])){?>
										$<?php echo number_format($totalupgraded_monthly['totalcredit'],2);?>
									<?php }else{ echo '$0.00';}?>
								</p>
  							</div>
						</div>
					</div>
				</div>
                <br/>
                <div class="row">
                	<div class="col-md-12">
                        <?php if(!$valid){?>
                        <div class="alert <?php echo $alert;?>" role="alert">
  							<?php echo $error;?>
						</div>
                        <?php }?>
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
						<a href="print.php?action=upgrade&start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&type=print" title="Print" class="btn btn-warning" target="__blank"><i class="bi bi-printer"></i></a>
						
                        <br/><br/>
						<div class="table-responsive custom-table-responsive">
                            <form method="get" id="myForm">
                            <input type="hidden" name="page" value="<?php echo $page;?>">
                            <table class="table custom-table" id="table-member">
                            	<thead>
            						<tr> 
              							<th scope="col" width="15%;">
                            				ID 	<a href="javascript:showsearch(1);"><i class="bi bi-filter-square-fill"></i></a>
                            					<?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='id'){
                                                        ?>
                                                        <a href="?shortby=id&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                            					<?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                if($_GET['shortby']=='id'){?>
                            				   		<a href="?shortby=id&short=desc">
                            							<i class="bi bi-sort-up"></i>
                           							</a>
                                                <?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc'){
                                                	if($_GET['shortby']=='id'){?>
                            				   		<a href="?shortby=id&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                    <?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=id&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="idsearch" id="search1" class="form-control sc" value="<?php echo $idsearch;?>">
                            				<input type="hidden" id="v1" value="0">
                            			</th>
                                        <th scope="col">
                            				Date
                            			</th>
              							<th scope="col" width="25%;">
                            				Email/Username<a href="javascript:showsearch(3);"><i class="bi bi-filter-square-fill"></i></a>
                                                    <?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='username'){
                                                        ?>
                                                        <a href="?shortby=username&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                                                    <?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                    if($_GET['shortby']=='username'){?>
                            				   			<a href="?shortby=username&short=desc">
                            								<i class="bi bi-sort-up"></i>
                           								</a>
                                                 	<?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc'){
                                                   	if($_GET['shortby']=='username'){?>
                            				   			<a href="?shortby=username&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=amount&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="username" id="search3" class="form-control sc" value="<?php echo $username;?>">
                            				<input type="hidden" id="v3" value="0">
                            			</th>
                                        <th scope="col">
                            				Description
                            			</th>
                                        <th scope="col">
                            				Amount
                            			</th>
										<th scope="col">
                            				Action
                            			</th>
            						</tr>
          						</thead>
                            	<tbody>
                            		<?php 
                                        $i=0;
                                        foreach($getdatarewards as $row){
                                            $i++;
                                            $md = $i%2;
                                            if($md==0){
                                            	$c ='gj';
                                            }else{
                                            	$c='';
                                            }
                                            ?>
                                            <tr>
                                                <td class="<?php echo $c;?>" align="center">#<?php echo $row['id'];?></td>
                                                <td align="center" class="<?php echo $c;?>"><?php echo $row['created_date'];?></td>
                                                <td align="center" class="<?php echo $c;?>"><?php echo $row['username'];?></td>
                                                <td align="center" class="<?php echo $c;?>"><?php echo $row['decription'];?></td>
                                                <td align="right" class="<?php echo $c;?>">$<?php echo number_format($row['credit'],2);?></td>
												<td align="center" class="<?php echo $c;?>"><a href="detail-user.php?id=<?php echo $row['user_id'];?>"><i class="bi bi-box-arrow-right"></i></a></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                            	</tbody>
                                <tfoot>
                                	<tr>
                                    	<td colspan="10">
                                    		<nav aria-label="Page navigation example">
  												<ul class="pagination">
                                    				<?php if($page>1){?>
    												<li class="page-item"><a class="page-link" href="?page=<?php echo $page-1;?>">Previous</a></li>
                                                    <?php }?>
                                    				<?php 
                                    					for($i=1;$i<=$totalpage;$i++){
                                    				?>
    													<li class="page-item"><a class="page-link" href="?page=<?php echo $i;?>"><?php echo $i;?></a></li>
                                                    <?php
                                                        
                                                     }?>
                                                    <?php if($totalpage>$page){?>
    												<li class="page-item"><a class="page-link" href="?page=<?php echo $page+1;?>">Next</a></li>
                                                    <?php }?>
  												</ul>
											</nav>
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