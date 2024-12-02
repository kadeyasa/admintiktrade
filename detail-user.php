<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
$title = "ADMIN DASHBOARD ";
session_start();
include('api.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
$valid =true;
include('header.php');
include('connection.php');
include('function.php');
if(isset($_GET['user_star'])){
    $star = $_GET['user_star'];
}else{
    $star = 'All';
}
$id = $_GET['id'];
if(!$id){
	header('location:login.php');
}
$data_member = getdata('member',$id,'id');
?>
<body>
	<?php include('menu.php');?>
	<br/>
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Detail User</li>
  			</ol>
		</nav>
		<div class="card">
			<div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h5>User Info</h5>
                        <div class="row">
							<div class="col-4">
								<p style="font-size:14px;">Total Deposit</p>
								<h6>
									<?php 
										$deposit = totaldeposit($id);
										echo '$ '.$deposit['totaldeposit'];
									?>
								</h6>
							</div>
                            <div class="col-4">
								<p style="font-size:14px;">Total Buy Energy</p>
								<h6>
									<?php 
										$energy = totalbuyenergy($id);
										echo '$ '.$energy['totalbuyenergy'];
									?>
								</h6>
							</div>
							<div class="col-4">
								<p style="font-size:14px;">Total Withdraw</p>
								<h6>
									<?php 
										$withdraw = totalwithdraw($id);
										echo '$ '.$withdraw['totalwithdraw'];
									?>
								</h6>
							</div>
                        </div>
						<div class="row">
							<div class="col-4">
								<p style="font-size:14px;">Total Balance</p>
								<h6>
									<?php 
										$balance = balanceuser($id);
										echo '$ '.$balance['balance'];
									?>
								</h6>
							</div>
                            <div class="col-4">
								<p style="font-size:14px;">Total Energy Balance</p>
								<h6>
									<?php 
										$balance = balanceuser($id);
										echo '$ '.$balance['pointcard_balance'];
									?>
								</h6>
							</div>
							<div class="col-4">
								<?php 
									$balancereward = balancerewarduser($id);
								?>
								<p style="font-size:14px;">Total Reward</p>
								<h6>
									<?php 
										$balancereward = balancerewarduser($id);
										echo '$ '.$balancereward['balance'];
									?>
								</h6>
							</div>
                        </div>
						<div class="row">
							<div class="col-4">
								<p style="font-size:14px;">Total Withdraw Reward</p>
								<h6>
									<?php 
										echo '$ '.$balancereward['totalwd'];
									?>
								</h6>
							</div>
                            <div class="col-4">
								<p style="font-size:14px;">Total Reward Balance</p>
								<h6>
									<?php 
										echo '$ '.$balancereward['remaining_balance'];
									?>
								</h6>
							</div>
							
                        </div>
						<hr/>
						<div class="row">
							<div class="col-12" style="font-size:14px;">
								<h5>Personal Info</h5>
								
								<div class="row">
									<div class="col-4">
										<div class="form-group">
											<label>Email : <?php echo $data_member['email'];?></label>
										</div>
										
									</div>
									<div class="col-4">
										
										<div class="form-group">
											<label>Username : <?php echo $data_member['username'];?></label>
										</div>
									</div>
									<div class="col-4">
										
										<div class="form-group">
											<label>User Star : <?php echo $data_member['user_star']-1;?></label>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
				    <div class="col-6">
                        <h5>Affliation Info</h5>
						<div class="row">
							<div class="col-4">
								<p style="font-size:14px;">Total Member</p>
								<h6>
									<?php 
										$teams = totalteams($id);
										echo $teams['totalteams'];
									?>
								</h6>
							</div>
                            <div class="col-4">
								<p style="font-size:14px;">Total Turnover</p>
								<h6>
									<?php 
										$turnover = teamturnover($id);
										echo '$ '.$turnover['totalturnover'];
									?>
								</h6>
							</div>
							<div class="col-4">
								<p style="font-size:14px;">Total Teamstrade</p>
								<h6>
									<?php echo '$ '.$teams['teamstrade']; ?>
								</h6>
							</div>
                        </div>
						<div class="row">
							<div class="col-4">
								<p style="font-size:14px;">Personal Trade</p>
								<h6>
									<?php echo '$ '.$data_member['personaltrade']; ?>
								</h6>
							</div>
                            
                        </div>
						<div class="row">
							<div class="col-12">
								<div id="chartContainer" style="width: 100%; height: 300px"></div>
							</div>
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
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script> 
<script type="text/javascript" src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script> 
<script type="text/javascript"> 
window.onload =function() { 
	$("#chartContainer").CanvasJSChart({ 
		title: { 
			text: "Teams by Country" 
		}, 
		data: [ 
		{ 
			type: "doughnut", 
			indexLabel: "{label}: {y}%",
			toolTipContent: "{label}: {y}%",
			dataPoints: [ 
				{ label: "Germany",       y: 16.6}, 
				{ label: "France",        y: 12.8}, 
				{ label: "United Kingdom",y: 12.3}, 
				{ label: "Italy",         y: 11.9}, 
				{ label: "Spain",         y: 9.0}, 
				{ label: "Poland",        y: 7.7}, 
				{ label: "Other (21 Countries)",y: 29.7} 
			] 
		} 
		] 
	}); 
} 
</script>
