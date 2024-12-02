<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - REWARD SETTING";
session_start();
//include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;
if(isset($_POST['save'])){
	if(isset($_POST['star']) && isset($_POST['reward']) && isset($_POST['status'])){
    	$postdata = array(
        	'star'=>$_POST['star'],
        	'reward'=>$_POST['reward'],
        	'status'=>$_POST['status']
        );
    	$respone = insertData('rewards_setup',$postdata);
    	$valid = false;
    	$alert = 'alert-success';
    	$error = 'Data has been saved';
    }else{
    	$valid = false;
    	$alert = 'alert-error';
    	$error = 'All data required';
    }
	//echo 'test';
}
if(isset($_GET['action'])){
	$action = $_GET['action'];
	if($action=='delete'){
    	//delete
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			$respone = deletedata('rewards_setup',$id);
			
			if($respone){
				$alert = 'alert-success';
				header('location:admin-reward-setting.php?response=success');
			}else{
				$alert = 'alert-error';
				$error='Failed to delete';
			}
    		
    		$error = $respone['message'];
		}
    }
	if($action=='edit'){
    	//delete
    }
}

if(isset($_GET['response'])){
	if($_GET['response']=='success'){
		$alert = 'success';
	}else{
		$alert = 'error';
	}
}
$results = getdatas('rewards_setup');
include('header.php');

?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Reward Setting</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>REWARD SETTING</h5>
				<a href="javascript:void(0);" class="btn btn-success add-setting">Add Setting</a>
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
                        				<th scope="col">STAR</th>
                        				<th scope="col">REWARD</th>
                        				<th scope="col">STATUS</th>
                        				<th scope="col">ACTION</th>
            						</tr>
          						</thead>
                            	<tbody>
                        			<?php 
                        				foreach($results as $row){
                                        ?>
                                        <tr> 
              								<td scope="col" align="center"><?php echo $row['id'];?></td>
                        					<th scope="col">User <?php echo $row['star']-1;?></th>
                        					<th scope="col">USD <?php echo number_format($row['reward']);?></th>
                        					<th scope="col"><?php echo $row['status']?"Publish":"Unpublish";?></th>
                        					<th scope="col">
                                        		 <a onclick="javascript :return window.confirm('Are you sure?');" href="?action=delete&id=<?php echo $row['id'];?>">Delete</a> 
                                        	</th>
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
        		<h5 class="modal-title" id="exampleModalLabel">Reward Setting</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">                                 
        		<div class="form-group">
            		<label>Star</label>
                	<select name="star" class="form-control">
						<option value="">-- Select Star--</option>
						<option value="2">Star 1</option>
						<option value="3">Star 2</option>
						<option value="4">Star 3</option>
						<option value="5">Star 4</option>
					</select>                                	
        	</div>
            <div class="form-group">
            	<label>Reward</label>
                <input type="text" name="reward" id="reward" class="form-control" required>                                  	
        	</div>  
            <div class="form-group">
            	<label>Status</label>
                <select name="status" class="form-control">
                    <option value="1">Publish</option>
                    <option value="0">Unpublish</option>
                    
                </select>                                 	
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
