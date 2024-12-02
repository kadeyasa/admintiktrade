<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - DEPOSIT ";
session_start();
include('api.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;

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

if(isset($_GET['username'])){
	$username = $_GET['username'];
	
}else{
	$username = '';
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
		'leg'=>$per_page,
		'start'=>$start,
		'searchby'=>'username',
		'keyword'=>$username
);
$respone = _postAPIlogin('admindeposit',$_SESSION['tokenadmin'],$_postdata);
//echo json_encode($respone);
$totalpage = ceil($respone['total']/$per_page);
include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Deposit</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Deposit</h5>
				<br/>
				<div class="row">	
					<div class="col-lg-3">
						<div class="card text-bg-primary mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-currency-dollar"></i>&nbsp;Deposit Success</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['totalsuccess'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-danger mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-currency-dollar"></i>&nbsp;Deposit Pending Send</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['totalpending'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-warning mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-currency-dollar"></i>&nbsp;Deposit Waiting </div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['totalwait'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-lg-3">
						<div class="card text-bg-default mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-currency-dollar"></i>&nbsp;Balance Admin TRC20</div>
  							<div class="card-body">
    							<p class="card-text"></p>
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
              							<th scope="col" width="20%;">
                            				Datetime <a href="javascript:showsearch(2);"><i class="bi bi-filter-square-fill"></i></a>
                                                	<?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='datetime'){
                                                        ?>
                                                        <a href="?shortby=datetime&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                                                	<?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                    if($_GET['shortby']=='datetime'){?>
                            				   			<a href="?shortby=datetime&short=desc">
                            								<i class="bi bi-sort-up"></i>
                           								</a>
                                                 	<?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc' ){
                                                   	if($_GET['shortby']=='datetime'){?>
                            				   			<a href="?shortby=datetime&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=datetime&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="namesearch" id="search2" class="form-control sc" value="<?php echo $namesearch;?>">
                            				<input type="hidden" id="v2" value="0">
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
                            				Amount
                            			</th>
                                        <th scope="col">
                            				Payment Address
                            			</th>            	
              							<th scope="col">
                            				Tx Hash
                            			</th>
              							<th scope="col">
                            				Status
                            			</th>
            						</tr>
          						</thead>
                            	<tbody>
                            		<?php
                                         $i=0;
                                         foreach($respone['data'] as $row){
                                         	$i++;
                                         	$md = $i%2;
                                         	if($md==0){
                                            	$c ='gj';
                                            }else{
                                            	$c='';
                                            }
                                         	$status = 'Pending';
                                         	if($row['status']==2){
                                            	$status='Pending to send';
                                            }
                                         
                                         	if($row['status']==1){
                                            	$status='Success';
                                            }
                                         
                                         	if($row['status']==-3){
                                            	$status='Waiting';
                                            }
                                     ?>
                                     <tr>
                                     	<td class="<?php echo $c;?>" align="center">#<?php echo $row['id'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['created_date'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['username'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['amount'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['payment_address'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['payment_hash'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $status;?></td>
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
    <!-- Modal -->
    <form method="post">                                               
	<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modal-editLabel" aria-hidden="true">
  		<div class="modal-dialog">
    		<div class="modal-content">
      			<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">Edit Member</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">                                 
        		<div class="form-group">
            		<label>Username</label>
                	<input type="text" name="username" id="username" class="form-control" required>                                  	
        	</div>
            <div class="form-group">
            	<label>Email</label>
                <input type="email" name="email" id="email" class="form-control" required>                                  	
        	</div>  
            <div class="form-group">
            	<label>Status</label>
                <select name="status" id="status" class="form-control">
                      <option value="1">Active</option>
                      <option value="0">Block</option>                          	
               	</select>                                	
        	</div>  
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        		<input type="submit" class="btn btn-primary" value="Save" value="1" name="save">
      		</div>
  		</div>
	</div> 
    <input type="hidden" name="id" id="iddata">
    </form>
</body>
<?php
include('footer.php');
?>
