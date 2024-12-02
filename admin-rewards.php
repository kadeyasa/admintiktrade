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
$totalbuy = getsumpointcardbuy()['totalcredit'];
$totalrewards =gettotalrewards()['difference'];
$totalclaim = totalclaim()['difference'];
$totalremaining = totalremaining()['totalbonus'];
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
if(isset($_GET['idsearch'])){
    $username = $_GET['username'];
    $keyword = $_GET['username'];
}else{
    $username ='';
    $keyword ='';
}
$idsearch='username';
$rewards = getdatarewards();
$getdatarewards = getdatarewards($start,$per_page,$keyword);
$totalpage = ceil(count($rewards)/$per_page);
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Rewards</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Rewards</h5>
				<br/>
				<div class="row">	
					<div class="col-lg-3">
						<div class="card text-bg-primary mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Total Buy Point Card</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($totalbuy);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-warning mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Total Rewards</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($totalrewards);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-danger mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Total Rewards Claim</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($totalclaim);?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-default mb-3" style="max-width: 18rem;">
  							<div class="card-header"><b><i class="bi bi-currency-dollar"></i>&nbsp;Rewards On Balance</b></div>
  							<div class="card-body">
    							<p class="card-text"><?php echo number_format($totalremaining);?></p>
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
                            				Rewards Balance
                            			</th>
                                        <th scope="col">
                            				Withdraw
                            			</th> 
										<th scope="col">
                            				Remaining Balance
                            			</th> 
            						</tr>
          						</thead>
                            	<tbody>
                            		<?php 
                                        foreach($getdatarewards as $row){
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id'];?></td>
                                                <td><?php echo $row['username'];?></td>
                                                <td><?php echo $row['balance'];?></td>
                                                <td><?php echo $row['totalwd'];?></td>
                                                <td><?php echo $row['remaining_balance'];?></td>
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
