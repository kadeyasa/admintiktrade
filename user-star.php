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
include('header.php');
include('connection.php');
include('function.php');
if(isset($_GET['user_star'])){
    $star = $_GET['user_star'];
}else{
    $star = 'All';
}
?>
<body>
	<?php include('menu.php');?>
	<br/>
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Member Star</li>
  			</ol>
		</nav>
		<div class="card">
			<div class="card-body">
				<h6>Member List Star <?php echo $star;?></h6>
                <div class="table-responsive custom-table-responsive">
                    <table width="100%;" class="table" style="font-size:12px;" id="user-star">
						<thead>
							<tr>
								<th>ID</th>
								<th>Username</th>
								<th>Email</th>
								<th>Upline</th>
								<th>Country</th>
								<th>Personal Trade</th>
								<th>TeamsTrade</th>
								<th>Action</th>
							</tr>
						</thead>
                    </table>
                </div>
  			</div>
		</div>
    </div>
</body>
<?php
include('footer.php');
?>
<script>
    var table = $('#user-star').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 100,
        "ajax": {
            "url": "data-star.php",  // Replace with the actual path to your PHP script
            "type": "POST",
            "data": function (d) {
                return $.extend({}, d, {
                    "limit": d.length,
                    "offset": d.start,
					"user_star":<?php echo $star;?>
                });
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "email" },
            { "data": "upline_id" },
        	{ "data": "country" },
        	{ "data": "personaltrade" },
        	{ "data": "teamstrade" },
			{ "data": "actions" }
        ]
    });
</script>