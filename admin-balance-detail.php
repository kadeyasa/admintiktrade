<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - ENERGY DETAIL ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;

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

if(isset($_GET['id'])){
    $user_id = $_GET['id'];
}else{
    header("location:admin-energy.php");
}

$results = getdatabalances('account_balance_histories','*',$user_id,$per_page,$start);
//echo json_encode($results);
$resultall = getdatabalances('account_balance_histories','*',$user_id);
$totalpage = ceil(count($resultall)/$per_page);
//summary data 
$sumdataid = summarytableaccountdeposit('account_deposit',$user_id);
$sumdataout = summarytableaccountbalance('account_balance_histories','credit',$user_id);
//echo json_encode($sumdata);
include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Account Balance Histories</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Account Balance Histories</h5>
				<br/>
				<div class="row">	
					<div class="col-lg-4">
						<div class="card text-bg-primary mb-4">
  							<div class="card-header"><b>Total Deposit</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($sumdataid['total'],2);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-4">
						<div class="card text-bg-warning mb-4">
  							<div class="card-header"><b>Total Balance Used</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($sumdataout['total'],2);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-4">
						<div class="card text-bg-warning mb-4">
  							<div class="card-header"><b>Balance</b></div>
  							<div class="card-body">
    							<p class="card-text">
									<?php echo number_format(($sumdataid['total']-$sumdataout['total']),2);?>
									&nbsp;
									<a href="update-balance.php?id=<?php echo $user_id;?>"><i class="bi bi-pencil-square"></i></a>
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
                        <div class="table-responsive custom-table-responsive">
                            <form method="get" id="myForm">
                            <input type="hidden" name="page" value="<?php echo $page;?>">
                            <table class="table custom-table" id="table-member">
                            	<thead>
            						<tr> 
              							<th>ID</th>
                                        <th>CREATED DATE</th>
                                        <th>DESCRIPTION</th>
                                        <th>AMOUNT</th>
            						</tr>
          						</thead>
                            	<tbody>
                            		<?PHP 
                                        $i=0;
                                        foreach($results as $key=>$row):
                                            $i++;
                                         	$md = $i%2;
                                         	if($md==0){
                                            	$c ='gj';
                                            }else{
                                            	$c='';
                                            }
                                            if($row['credit']>0){
                                                $amount = $row['credit'];
                                            }else{
                                                $amount ='-'.$row['debet'];
                                            }
                                    ?>
                                    <tr> 
              							<td class="<?php echo $c;?>">#<?PHP echo $row['id'];?></td>
                                        <td class="<?php echo $c;?>"><?php echo $row['created_date'];?></td>
                                        <td class="<?php echo $c;?>"><?php echo $row['decription'];?></td>
                                        <td class="<?php echo $c;?>"><?php echo $amount;?></td>
            						</tr>
                                    <?PHP
                                        endforeach;
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
