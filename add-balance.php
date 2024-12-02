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
$valid =false;

if(isset($_POST['search'])){
    $username = $_POST['username'];
    if($username!=''){
        $check = checkusername($username);
        if($check){
            $val_user = $check['username'];
        }else{
            $val_user = '';
        }
    }
}else{
    $val_user='';
}
if(isset($_POST['save'])){
    $username = $_POST['username'];
    if($username!=''){
        $check = checkusername($username);
        if($check){
            $checkbalance = balanceuser($check['id']);
            if($checkbalance){
                //update balance 
                $amount = $_POST['amount'];
                $description = $_POST['description'];
                if($amount>0){
                    //update balance
                    $data = array(
                        'balance'=>$checkbalance['balance']+$amount,
                        //'remaining_balance'=>$checkbalance['remaining_balance']+$amount
                    );
                    //where 
                    $conditions = array(
                        'user_id'=>$check['id']
                    );
                    $update = updateData('account_balance',$data,$conditions);
                    if($update){
                        //add histories 
                        $data = array(
                            'type'=>1,
                            'user_id'=>$check['id'],
                            'created_date'=>date('Y-m-d H:i:s'),
                            'decription'=>$description,
                            'debet'=>$amount
                        );
                        //insert 
                        $insert = insertData('account_balance_histories',$data);
                        $valid = true;
                        $alert = 'alert-success';
                        $error = 'Data has been saved';
                    }
                }
            }
        }
    }
}
include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		
		<div class="card">
  			<div class="card-body">
    			<h5>Add Reward</h5>
				
                <div class="row">
                	<div class="col-md-12">
                        <?php if($valid){?>
                        <div class="alert <?php echo $alert;?>" role="alert">
  							<?php echo $error;?>
						</div>
                        <?php }?>
                        <div class="table-responsive custom-table-responsive">
                            <form method="post" id="myForm">
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" value="<?php echo $val_user;?>" <?php if($val_user!=''){ echo 'readonly';}?> aria-describedby="basic-addon2" name="username">
                                        <button name="search" class="input-group-text" id="basic-addon2">Search</button>
                                    </div>                                	
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" id="description" placeholder="Description" aria-describedby="description" name="description">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" id="amount" placeholder="Amount" aria-describedby="amount" name="amount">
                                </div>
                                <div class="form-group mb-3">
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
