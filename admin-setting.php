<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - BONUS SETUP ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;
$action ='';
$withdraw_min='';
$withdraw_fee='';
$deposit_min='';
$deposit_bep='';
$deposit_trc='';
$deposit_min_wd='';

if(isset($_POST['save'])){
    $withdraw_min=$_POST['withdraw_min'];
    $withdraw_fee=$_POST['withdraw_fee'];
    $deposit_min=$_POST['deposit_min'];
    $deposit_bep=$_POST['deposit_bep'];
    $deposit_trc=$_POST['deposit_trc'];
    $deposit_min_wd=$_POST['deposit_min_wd'];

    if($withdraw_min!='' && $withdraw_fee !='' && $deposit_min!='' && $deposit_bep!='' && $deposit_trc!='' && $deposit_min_wd!=''){
        $data = [
            'withdraw_min' => $withdraw_min,
            'withdraw_fee' => $withdraw_fee,
            'deposit_min' => $deposit_min,
            'deposit_bep_address' => $deposit_bep,
            'deposit_trc_address' => $deposit_trc,
            'deposit_min_wd'=>$deposit_min_wd
            //'status' => 1
        ];

        if(isset($_GET['action'])){
            if($_GET['action']=='edit'){
                $where = array(
                    'id'=>$_GET['id']
                );
                $save = updateData('system_setting',$data,$where);
                $action='save';
            }
        }else{
            $save = insertData('system_setting',$data);
        }

        if($save){
            $valid = false;
            $error ='Success to save data';
            $alert = 'alert-success';
        }else{
            $valid = false;
            $error ='Failed to save data';
            $alert = 'alert-error';
        }
    }else{
        $valid = false;
        $error ='Failed to save data';
        $alert = 'alert-error';
    }

    
    //header("location:admin-setting.php");
}


if(isset($_GET['action']) && $action==''){
    $action = $_GET['action'];
    
    if($action=='delete'){
        $id = $_GET['id'];
        if($id){
            if(deletedata('system_setting',$id)){
                $valid = false;
                $error ='Success to deleted data';
                $alert = 'alert-success';
            }else{
                $valid = false;
                $error ='Failed to save delete';
                $alert = 'alert-error';
            }
        }else{
            $valid = false;
            $error ='Failed to save delete';
            $alert = 'alert-error';
        }
    }

    if($action=='edit'){
        $id = $_GET['id'];
        $table = 'system_setting';
        $row = getdata($table, $id);;
        //echo json_encode($row);
        $withdraw_min = $row['withdraw_min'];
        $withdraw_fee = $row['withdraw_fee'];
        $deposit_min = $row['deposit_min'];
        $deposit_bep = $row['deposit_bep_address'];
        $deposit_trc = $row['deposit_trc_address'];
        $deposit_min_wd = $row['deposit_min_wd'];
    }
    
    if($action=='createaddress'){
        $post_data = array(
            'network'=>$_GET['network']
        );
        $respone = _postAPIlogin('createadminaddress',$_SESSION['tokenadmin'],$post_data);
        //echo json_encode($respone);
    }
}

$results = getdatas('system_setting','*',array(),'id ASC');

if($results==null){
    $results = array();
}

include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Membership</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Setup Bonus</h5>
				<a href="javascript:void(0);" class="btn btn-success add-setting">Add Setting</a>
                <a href="admin-setting.php?action=createaddress&network=bep" class="btn btn-warning">Create Bep Setting</a>
                <a href="admin-setting.php?action=createaddress&network=trc" class="btn btn-warning">Create TRC20 Setting</a>
                <br/><br/>
                <div class="row">
                	<div class="col-md-12">
                        <?php if(!$valid){?>
                        <div class="alert <?php echo $alert;?>" role="alert">
  							<?php echo $error;?>
						</div>
                        <?php }?>
                        <?php 
                            if(isset($respone)){
                                if($respone['error']==0){
                                    echo 'BEP Privatekey :'.$respone['results']['bep_private'].'<br/>';
                                    echo 'TRON Privatekey :'.$respone['results']['trc_private'].'<br/><br/>';
                                }
                            }
                        ?>
                        <div class="table-responsive custom-table-responsive">
                            <form method="get" id="myForm">
                            <input type="hidden" name="page" value="<?php echo $page;?>">
                            <table class="table custom-table" id="table-member">
                            	<thead>
            						<tr> 
              							<th scope="col">ID</th>
                        				<th scope="col">MINIMUM WITHDRAW</th>
                        				<th scope="col">WITHDRAW FEE</th>
                        				<th scope="col">MINIMUM DEPOSIT</th>
                                        <th scope="col">DEPOSIT BEP20 ADDRESS SETUP</th>
                                        <th scope="col">DEPOSIT TRC20 ADDRESS SETUP</th>
                                        <th scope="col">ADMIN BEP20</th>
                                        <th scope="col">ADMIN TRC20</th>
                        				<th scope="col">ACTION</th>
            						</tr>
          						</thead>
                            	<tbody>
                        			<?php 
                        				foreach($results as $row){
                                        ?>
                                        <tr> 
              								<td scope="col" align="center"><?php echo $row['id'];?></td>
                        					<td scope="col"><?php echo $row['withdraw_min'];?></td>
                        					<td scope="col"><?php echo $row['withdraw_fee'];?></td>
                                            <td scope="col"><?php echo $row['deposit_min'];?></td>
                                            <td scope="col"><?php echo $row['deposit_bep_address'];?></td>
                                            <td scope="col"><?php echo $row['deposit_trc_address'];?></td>
                                            <td scope="col"><?php echo $row['bep_admin_address'];?></td>
                                            <td scope="col"><?php echo $row['trc_admin_address'];?></td>
                        					<td scope="col">
                                        		 <a href="?action=edit&id=<?php echo $row['id'];?>">EDIT</a> | <a onclick="javascript :return window.confirm('Are you sure?');" href="?action=delete&id=<?php echo $row['id'];?>">Delete</a> 
                                        	</td>
            							</tr>
                                   	<?php
                                        }
                        			?>
                            	</tbody>
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
        <div class="modal fade" id="modal-membership" tabindex="-1" aria-labelledby="modal-editLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Setup Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">                                 
                    <div class="form-group">
                        <label>Minimum Withdraw</label>
                        <input type="text" value="<?php echo $withdraw_min;?>" name="withdraw_min" id="withdraw_min" class="form-control" required>                                  	
                </div>
                <div class="form-group">
                    <label>Withdraw Fee</label>
                    <input type="text"  value="<?php echo $withdraw_fee;?>" name="withdraw_fee" id="withdraw_fee" class="form-control" required>                                  	
                </div>  
                <div class="form-group">
                    <label>Minimum Deposit</label>
                    <input type="text"  value="<?php echo $deposit_min;?>" name="deposit_min" id="deposit_min" class="form-control" required>                                  	
                </div>  
                <div class="form-group">
                    <label>Deposit BEP20 Address</label>
                    <input type="text"  value="<?php echo $deposit_bep;?>" name="deposit_bep" id="deposit_bep" class="form-control" required>                                  	
                </div>  
                <div class="form-group">
                    <label>Deposit TRC20 Address</label>
                    <input type="text"  value="<?php echo $deposit_trc;?>" name="deposit_trc" id="deposit_trc" class="form-control" required>                                  	
                </div> 
                <div class="form-group">
                    <label>Minimum Withdraw Deposit</label>
                    <input type="text"  value="<?php echo $deposit_min_wd;?>" name="deposit_min_wd" id="deposit_min_wd" class="form-control" required>                                  	
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
<script type="text/javascript">
    <?php 
        if(isset($action) && $action=='edit'){
            ?>
            $(document).ready(function(){
                $('#modal-membership').modal('show');
            })
            <?php
        }
    ?>
    
</script>