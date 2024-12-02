<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN DASHBOARD ";
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

if(isset($_GET['ref_code_search'])){
	$ref_code = $_GET['ref_code_search'];
	
}else{
	$ref_code = '';
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
		'ref_code_search'=>$ref_code,
		'sort'=>$sort,
		'leg'=>$per_page,
		'start'=>$start
);
$respone = _postAPIlogin('resultallmember',$_SESSION['tokenadmin'],$_postdata);
//echo json_encode($respone);
$totalpage = ceil($respone['data']['total']/$per_page);
if(isset($_GET['daterange'])){
	$daterange = $_GET['daterange'];
	$daterange = str_replace(" ","",$daterange);
	$dateranges = explode("-",$daterange);
	$start_date = str_replace("/","-",$dateranges[0]);
	$end_date = str_replace("/","-",$dateranges[1]);
	//$totalupgraded = getmemberupgraded($start_date,$end_date);
}else{
	$daterange = '';
	$start_date = date('Y-m-01');
	$end_date =date('Y-m-t');
	//$totalupgraded = getmemberupgraded();
}
include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Member</h5>
				<br/>
				<div class="row">	
					<div class="col-md-3">
						<div class="card text-bg-primary mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-person"></i>&nbsp;Total Member</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['data']['total'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-md-3">
						<div class="card text-bg-danger mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-person"></i>&nbsp;Total Active</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['data']['totalactive'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-md-3">
						<div class="card text-bg-warning mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-person"></i>&nbsp;Today Register</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['data']['totaltoday'];?></p>
  							</div>
						</div>
					</div>
                    <div class="col-md-3">
						<div class="card text-bg-default mb-3" style="max-width: 18rem;">
  							<div class="card-header"><i class="bi bi-person"></i>&nbsp;Today Active</div>
  							<div class="card-body">
    							<p class="card-text"><?php echo $respone['data']['totaltodayactive'];?></p>
  							</div>
						</div>
					</div>
				</div>
				<hr/>
                <form method="get">
					<div class="input-group mb-3">
						
						<input type="text" class="form-control" placeholder="Date Range" aria-label="Recipient's username" aria-describedby="basic-addon2" name="daterange" value="<?php echo $daterange;?>">
						<div class="input-group-append">
							<button class="input-group-text" id="basic-addon2">Cari</button>
						</div>
					</div>
				</form>
				<a href="member-print.php?action=upgrade&start_date=<?php echo $start_date;?>&end_date=<?php echo $end_date;?>&type=print" title="Print" class="btn btn-warning" target="__blank"><i class="bi bi-printer"></i></a>
				<br/><br/>	
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
										<th>Created Date</th>
              							<th scope="col" width="20%;">
                            				Username <a href="javascript:showsearch(2);"><i class="bi bi-filter-square-fill"></i></a>
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
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc' ){
                                                   	if($_GET['shortby']=='username'){?>
                            				   			<a href="?shortby=username&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=username&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="namesearch" id="search2" class="form-control sc" value="<?php echo $namesearch;?>">
                            				<input type="hidden" id="v2" value="0">
                            			</th>
              							<th scope="col" width="25%;">
                            				Email <a href="javascript:showsearch(3);"><i class="bi bi-filter-square-fill"></i></a>
                                                    <?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='email'){
                                                        ?>
                                                        <a href="?shortby=email&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                                                    <?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                    if($_GET['shortby']=='email'){?>
                            				   			<a href="?shortby=email&short=desc">
                            								<i class="bi bi-sort-up"></i>
                           								</a>
                                                 	<?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc'){
                                                   	if($_GET['shortby']=='email'){?>
                            				   			<a href="?shortby=email&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=email&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="emailsearch" id="search3" class="form-control sc" value="<?php echo $emailsearch;?>">
                            				<input type="hidden" id="v3" value="0">
                            			</th>
										<th scope="col" width="25%;">
                            				Ref Code <a href="javascript:showsearch(4);"><i class="bi bi-filter-square-fill"></i></a>
                                                    <?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='email'){
                                                        ?>
                                                        <a href="?shortby=email&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                                                    <?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                    if($_GET['shortby']=='ref_code'){?>
                            				   			<a href="?shortby=ref_code&short=desc">
                            								<i class="bi bi-sort-up"></i>
                           								</a>
                                                 	<?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc'){
                                                   	if($_GET['shortby']=='ref_code'){?>
                            				   			<a href="?shortby=ref_code&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=ref_code&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				<input type="text" name="ref_code_search" id="search4" class="form-control sc" value="<?php echo $ref_code;?>">
                            				<input type="hidden" id="v4" value="0">
                            			</th>
              							<th scope="col">
                            				Turnover
                            			</th>
              							<th scope="col">
                            				Upline
                            			</th>
                                        <th scope="col">
                                                	<?php 
                            						if(isset($_GET['shortby'])){
                                                    	if($_GET['shortby']!='status'){
                                                        ?>
                                                        <a href="?shortby=status&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                        <?php
                                                        }
                                                    }
                            					?>
                                                	<?php if(isset($_GET['shortby']) && $_GET['short']=='asc'){
                                                    if($_GET['shortby']=='status'){?>
                            				   			<a href="?shortby=status&short=desc">
                            								<i class="bi bi-sort-up"></i>
                           								</a>
                                                 	<?php }?>
                                               	<?php }else if(isset($_GET['shortby']) && $_GET['short']=='desc' ){
                                                   	if($_GET['shortby']=='status'){?>
                            				   			<a href="?shortby=status&short=asc">
                            								<i class="bi bi-sort-down"></i>
                           								</a>
                                                   	<?php }?>
                                               	<?php }else{?>
                                                	<a href="?shortby=status&short=asc">
                            							<i class="bi bi-sort-down"></i>
                           							</a>
                                                <?php }?>
                            				
                            				Status
                            			</th>
										<th scope="col">
                            				Rank
                            			</th>
										<th scope="col">
                            				Status Member
                            			</th>
										<th scope="col">
                            				Subscribe Until
                            			</th>
                            			<th scope="col">
                            				Action
                            			</th>
            						</tr>
          						</thead>
                            	<tbody>
                            		<?php
                                         $i=0;
                                         foreach($respone['data']['data'] as $row){
                                         	if($row['status']=='1'){
                                            	$status ='Active';
                                            }else{
                                            	$status = 'Unactive';
                                            }
                                         	$i++;
                                         	$md = $i%2;
                                         	if($md==0){
                                            	$c ='gj';
                                            }else{
                                            	$c='';
                                            }
                                     ?>
                                     <tr>
                                     	<td class="<?php echo $c;?>"><?php echo $row['id'];?></td>
										 <td class="<?php echo $c;?>"><?php echo $row['created_date'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['username'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['email'];?></td>
										 <td class="<?php echo $c;?>"><?php echo $row['ref_code'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['turnover'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $row['upline_id'];?></td>
                                     	<td class="<?php echo $c;?>"><?php echo $status;?></td>
										<td class="<?php echo $c;?>">Star <?php echo $row['user_star']?($row['user_star']-1):'No Activated';?></td>
										<td class="<?php echo $c;?>"><?php echo $row['member_type']?'Pro Member':'Free Member';?></td>
										<td class="<?php echo $c;?>"><?php echo $row['date_subscribe'];?></td>
                                     	<td align="center" class="<?php echo $c;?>">
                                     		<a href="javascript:deletemember('<?php echo $row['id'];?>');"><i class="bi bi-trash3-fill"></i></a>
                                     		<a href="edit-member.php?id=<?php echo $row['id'];?>"><i class="bi bi-pencil-square"></i></a>
                                     		<a href="detail-user.php?id=<?php echo $row['id'];?>"><i class="bi bi-box-arrow-right"></i></a>
                                     	</td>
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
