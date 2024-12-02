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
	$username = $_POST['username'];
	$email = $_POST['email'];
	$id = $_POST['id'];
	if($username!='' && $email!=''){
    	$status = $_POST['status'];
		if($_POST['date_sub']!=''){
    		$postdata = array(
        		'status'=>$status,
				'id'=>$id,
				'email'=>$email,
				'username'=>$username,
				'user_star'=>$_POST['user_star'],
				'date_subscribe'=>$_POST['date_sub'],
				'member_type'=>$_POST['member_type']
        	);
		}else{
			$postdata = array(
        		'status'=>$status,
				'id'=>$id,
				'email'=>$email,
				'username'=>$username,
				'user_star'=>$_POST['user_star'],
				//'date_subscribe'=>$_POST['date_sub'],
				//'member_type'=>$_POST['member_type']
        	);
		}
    	$response = _postAPILogin("updatedmember",$_SESSION['tokenadmin'],$postdata);
    	if($response['error']==0){
        	$valid = false;
        	$alert = 'alert-success';
        	$error = $response['message'];
        }else{
        	$valid = false;
        	$alert = 'alert-danger';
        	$error = $response['message'];
        }
    }else{
    	$valid = false;
    	$error = 'Invalid Username or password';
    	$alert = 'alert-danger';
    }
}
if(isset($_GET['id'])){
    $postdata = array(
    	'id'=>$_GET['id']
    );
	$member_data = _postAPILogin('getdatamember',$_SESSION['tokenadmin'],$postdata);
    //echo json_encode($member_data);
}else{
    header('location:index.php');
}

include('header.php');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Edit Member</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Member</h5>
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
                            <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" readonly value="<?php echo $member_data['data']['username'];?>" name="username" id="username" class="form-control" required>                                  	
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?php echo $member_data['data']['email'];?>" >                                  	
                            </div>  
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" <?php if($member_data['data']['status']=='1'){ echo 'selected';}?>>Active</option>
                                    <option value="0" <?php if($member_data['data']['status']=='0'){ echo 'selected';}?>>Block</option>                          	
                                </select>                                	
                            </div>  
                            <div class="form-group">
                                <label>User Star</label>
                                <select name="user_star" class="form-control">
                                    <option value="1" <?php if($member_data['data']['user_star']=='1'){ echo 'selected';}?>>No Star</option>
                                    <option value="2" <?php if($member_data['data']['user_star']=='2'){ echo 'selected';}?>>Star 1</option>
                                    <option value="3" <?php if($member_data['data']['user_star']=='3'){ echo 'selected';}?>>Star 2</option>
                                    <option value="4" <?php if($member_data['data']['user_star']=='4'){ echo 'selected';}?>>Star 3</option>
                                    <option value="5" <?php if($member_data['data']['user_star']=='5'){ echo 'selected';}?>>Star 4</option>
                                </select>
                                                                 	
                            </div>
                            <div class="form-group">
                                <label>Member Type</label>
                                <select name="member_type" id="member_type" class="form-control">
                                    <?php 
                                        if(is_null($member_data['data']['member_type'])){
                                            $member_type=0;
                                        }else{
                                            $member_type =$member_data['data']['member_type'];
                                        }
                                    ?>
                                    <option value="1" <?php if($member_type=='1'){ echo 'selected';}?>>Pro Member</option>
                                    <option value="0" <?php if($member_type=='0'){ echo 'selected';}?>>Free Member</option>                          	
                                </select>                                  	
                            </div>
                            <div class="form-group">
                                <?php 
                                    if(is_null($member_data['data']['date_subscribe'])){
                                        $date_subscribe='';
                                    }else{
                                        $date_subscribe=$member_data['data']['date_subscribe'];
                                    }
                                ?>
                                <label>Date Sub</label>
                                <input type="text" name="date_sub" id="date_sub" class="form-control" value="<?php echo $date_subscribe;?>">                                  	
                            </div>
                            <br/>
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
