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
$userstar='';
$bonus='';
$globalbonus='';

if(isset($_POST['save'])){
    $user_star=$_POST['user_star'];
    $bonus=$_POST['bonus'];
    $globalbonus=$_POST['globalbonus'];
    if($user_star!='' && $bonus !='' && $globalbonus!=''){
        $data = [
            'user_star' => $user_star,
            'bonus' => $bonus,
            'globalbonus' => $globalbonus,
            //'status' => 1
        ];

        if(isset($_GET['action'])){
            if($_GET['action']=='edit'){
                $where = array(
                    'id'=>$_GET['id']
                );
                $save = updateData('setupbonuslevel',$data,$where);
            }
        }else{
            $save = insertData('setupbonuslevel',$data);
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

    
    header("location:bonus-setup.php");
}


if(isset($_GET['action']) && $action==''){
    $action = $_GET['action'];
    $id = $_GET['id'];
    if($action=='delete'){
        if($id){
            if(deletedata('setupbonuslevel',$id)){
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
        $table = 'setupbonuslevel';
        $row = getdata($table, $id);;
        //echo json_encode($row);
        $userstar = $row['user_star'];
        $bonus = $row['bonus'];
        $globalbonus=$row['globalbonus'];
    }
    
}
$results = getdatas('setupbonuslevel','*',array(),'user_star ASC');
//echo json_encode($results);
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
				<a href="javascript:void(0);" class="btn btn-success add-setting">Add Bonus</a>
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
              							<th scope="col">ID</th>
                        				<th scope="col">USER STAR</th>
                        				<th scope="col">BONUS</th>
                        				<th scope="col">GLOBAL BONUS</th>
                        				<th scope="col">ACTION</th>
            						</tr>
          						</thead>
                            	<tbody>
                        			<?php 
                        				foreach($results as $row){
                                        ?>
                                        <tr> 
              								<td scope="col" align="center"><?php echo $row['id'];?></td>
                        					<td scope="col">Star <?php echo ($row['user_star']-1);?></td>
                        					<td scope="col"><?php echo number_format($row['bonus']);?> %</td>
                                            <td scope="col"><?php echo $row['globalbonus']?number_format($row['globalbonus']):0;?>%</td>
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
        		<h5 class="modal-title" id="exampleModalLabel">Bonus Setup</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">                                 
        		<div class="form-group">
            		<label>User Star</label>
                	<input type="text" value="<?php echo $userstar;?>" name="user_star" id="user_star" class="form-control" required>                                  	
        	</div>
            <div class="form-group">
            	<label>Bonus</label>
                <input type="text"  value="<?php echo $bonus;?>" name="bonus" id="user_star" class="form-control" required>                                  	
        	</div>  
            <div class="form-group">
            	<label>Globalbonus</label>
                <input type="text"  value="<?php echo $globalbonus;?>"name="globalbonus" id="globalbonus" class="form-control" required>                                  	
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