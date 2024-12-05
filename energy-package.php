<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN - MEMBER ENERGY ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;
if(isset($_POST['save'])){
    $package_name=$_POST['package_name'];
    $price=$_POST['price'];
    $energy=$_POST['energy'];
    if($package_name!='' && $price !='' && $energy!=''){
        if(insertpackage($package_name,$price,$energy)){
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
}
if(isset($_GET['action'])){
    $action = $_GET['action'];
    if($action=='delete'){
        $id = $_GET['id'];
        if($id){
            if(deletedata('pointcard_package',$id)){
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
}
$results = getenergypackages();
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
    			<h5>Energy Package</h5>
				<a href="javascript:void(0);" class="btn btn-success add-setting">Add Package</a>
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
                        				<th scope="col">PACKAGE NAME</th>
										<th scope="col">CREATED AT</th>
										<th scope="col">UPDATED AT</th>
                        				<th scope="col">PACKAGE</th>
                        				<th scope="col">PROFIT</th>
                        				<th scope="col">ACTION</th>
            						</tr>
          						</thead>
                            	<tbody>
                        			<?php 
                        				foreach($results as $row){
                                        ?>
                                        <tr> 
              								<td scope="col" align="center"><?php echo $row['id'];?></td>
											  <td scope="col"><?php echo $row['package_name'];?></td>

                        					<td scope="col">USD <?php echo number_format($row['price']);?></td>
                                            <td scope="col"><?php echo number_format($row['getpoint']);?>%</td>
                        					<td scope="col">
                                        		 <a onclick="javascript :return window.confirm('Are you sure?');" href="?action=delete&id=<?php echo $row['id'];?>">Delete</a> 
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
        		<h5 class="modal-title" id="exampleModalLabel">Energy Package</h5>
        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      		</div>
      		<div class="modal-body">                                 
        		<div class="form-group">
            		<label>Package Name</label>
                	<input type="text" name="package_name" id="package_name" class="form-control" required>                                  	
        	</div>
            <div class="form-group">
            	<label>Price</label>
                <input type="text" name="price" id="price" class="form-control" required>                                  	
        	</div>  
            <div class="form-group">
            	<label>Energy Amount</label>
                <input type="text" name="energy" id="energy" class="form-control" required>                                  	
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