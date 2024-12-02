<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = "ADMIN DASHBOARD ";
session_start();
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $user_balance = getbalanceuser($_GET['id']);
}

if(isset($_POST['save'])){
	$usd_balance = $_POST['usd_balance'];
    $energy_balance = $_POST['energy_balance'];
    $update = _updatebalance($usd_balance,$energy_balance,$id);
    if($update){
        $alert = 'alert-success';
        $error = 'Data has been saved';
    }else{
        $alert = 'alert-error';
        $error = 'Failed to save data';
    }
    $user_balance = getbalanceuser($_GET['id']);
}


include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Update Balance</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Update Balance</h5>
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
                                <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                <div class="form-group">
                                    <label>USD Balance</label>
                                    <input type="text" value="<?php echo $user_balance['balance'];?>" name="usd_balance" id="used_balance" class="form-control" required>                                  	
                                </div>
                                <div class="form-group">
                                    <label>Energy Balance</label>
                                    <input type="text" value="<?php echo $user_balance['pointcard_balance'];?>" name="energy_balance" id="energy_balance" class="form-control" value="" >                                  	
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
