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


if(isset($_POST['save'])){
	if(isset($_POST['getpoint'])){
        $profit = $_POST['getpoint'];
        $package = $_POST['package'];
        if($package=='all'){
            //update 
            $data = array(
                'getpoint'=>$profit,
                'updated_date'=>date('Y-m-d H:i:s')
            );
            $conditions = array();
            updateData('pointcard_package',$data,$conditions);
        }else{
            //update 
            $data = array(
                'getpoint'=>$profit,
                'updated_date'=>date('Y-m-d H:i:s')
            );
            $conditions = array(
                'price'=>$package
            );
            updateData('pointcard_package',$data,$conditions);
        }
    }
}   

include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Edit Package</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Package</h5>
				<br/>
				
                <br/>
                <div class="row">
                	<div class="col-md-12">
                        <?php if(!$valid){?>
                        <div class="alert <?php echo $alert;?>" role="alert">
  							<?php echo $error;?>
						</div>
                        <?php }?>
                        <div class="table-responsive custom-table-responsive">
                            <form method="post" id="myForm">
                            <div class="form-group">
                                <label>Package</label>
                                <select name="package" class="form-control">
                                    <option value="all">All Package</option>
                                    <?php 
                                        $results = getenergypackages();
                                        foreach($results as $item){
                                            ?>
                                            <option value="<?php echo $item['id'];?>"><?php echo $item['price'];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>                                	
                            </div>
                            <div class="form-group">
                                <label>Update Profit</label>
                                <input type="text" value="" name="getpoint" id="getpoint" class="form-control" required>                                  	
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="btn btn-primary" value="Save" value="1" name="save">
                            </div>
                            
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
