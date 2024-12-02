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
getdatamemberdashboard();
?>
<body>
	<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
  			</ol>
		</nav>
		<div class="card">
			<div class="card-body">
				<h6>Money IN-Flow</h6>
				<div class="table-responsive custom-table-responsive">
					<table width="100%" class="table" style="font-size:12px;">
						<thead class="thead-dark">
							<tr>
								<th scope="col"><h6>Total Deposit</h6></th>
								
							</tr>
							<tr>
								<td>
									<?php 
										$data = summarydeposit();
										echo '<h6> $'.number_format($data['totaldeposit'],2).'</h6>';
									?>
								</td>
							</tr>
						</thead>
					</table>
				</div>
				<h6>Money Out-Flow</h6>
				<div class="table-responsive custom-table-responsive">
					<?php 
						$summarybuyenergy = summarybuyenergy();
						$summaryupgrade = summaryupgrade();
						$summarywithdraw = summarywithdraw();
						$summarybalance = summarymemberbalance();
						$summaryrewardbalance = summaryrewardbalance();
						$total = $summarybuyenergy['totalbuyenergy']+$summaryupgrade['totalupgrade']+$summarywithdraw['totalwithdraw']+$summarybalance['totalbalance'];
					?>
					<table width="100%" class="table" style="font-size:12px;">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Total Buy Energy</th>
								<th scope="col">Amoun Member Upgrade</th>
								<th scope="col">Total Withdraw</th>
								<th scope="col">Member Balance Active</th>
								<th scope="col">Member Reward Balance</th>
								<th scope="col">Total Out-Flow</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><b>$<?php echo $summarybuyenergy['totalbuyenergy'];?></b></td>
								<td><b>$<?php echo $summaryupgrade['totalupgrade'];?></b></td>
								<td><b>$<?php echo $summarywithdraw['totalwithdraw'];?></b></td>
								<td><b>$<?php echo $summarybalance['totalbalance'];?></td>
								<td><b>$<?php echo $summaryrewardbalance['totalreward'];?></td>
								<td><b>$<?php echo $total;?></td>
							</tr>
						</tbody>
					</table>
				</div>
  			</div>
		</div>
		<br/>
		<div class="card">
			<div class="card-body">
				<h6>User Star</h6>
				<div class="table-responsive custom-table-responsive">
					<table width="100%" class="table" style="font-size:12px;">
						<tr>
							<th>Star 1</th>
							<th>Star 2</th>
							<th>Star 3</th>
							<th>Star 4</th>
						</tr>
						<tr>
							<td>
								<?php 
									$star = checkuserstart(2);
								?>
								<a href="user-star.php?user_star=2" class="btn btn-primary">
									<?php 
										echo $star['totalmember'];
									?>
								</a>
							</td>
							<td>
								<a href="user-star.php?user_star=3" class="btn btn-success">
								<?php 
									$star = checkuserstart(3);
									echo $star['totalmember'];
								?>
								</a>
							</td>
							<td>
								<a href="user-star.php?user_star=4" class="btn btn-warning">
								<?php 
									$star = checkuserstart(4);
									echo $star['totalmember'];
								?>
								</a>
							</td>
							<td>
								<a href="user-star.php?user_star=5" class="btn btn-danger">
									<?php 
										$star = checkuserstart(5);
										echo $star['totalmember'];
									?>
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<br/>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-6"><h6>Member Statistic</h6></div>
						<div class="col-6 text-right">
							<select class="form-control" name="viewby" style="font-size:12px;" onchange="memberchange(this.value);">
								<option value="">View Statistic</option>
								<option value="month">Monthly</option>
								<option value="daterange">Range</option>
							</select>
							<select name="month" id="month1" style="display:none; margin-top:10px;font-size:12px;" class="form-control">
								<option value="01">Januari</option>
								<option value="02">Pebruari</option>
								<option value="03">Maret</option>
								<option value="04">April</option>
								<option value="05">Mei</option>
								<option value="06">Juni</option>
								<option value="07">Juli</option>
								<option value="08">Agustus</option>
								<option value="09">September</option>
								<option value="10">Oktober</option>
								<option value="11">November</option>
								<option value="12">Desember</option>
							</select>
							<br/>
							<div class="range1" style="display:none; margin-top:10px;font-size:12px;" >
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>Start Date</label>
											<input style="font-size:12px;" class="datepicker3" type="text" name="start_date1" class="form-control" id="deposit_start">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>End Date</label>
											<input style="font-size:12px;" class="datepicker4" type="text" name="end_date1" class="form-control" id="end_start">
										</div>
									</div>
								</div>
								<br/>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div id="chartContainer2" style="height: 300px; width: 100%;"></div>
					</div>
				</div>
			</div>
	</div>
	<br/>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-6"><h6>Deposit & Energy Deposit Statistic</h6></div>
					<div class="col-6 text-right">
						<select class="form-control" name="viewby" style="font-size:12px;" onchange="depositchange(this.value);">
							<option value="">View Statistic</option>
							<option value="month">Monthly</option>
							<option value="daterange">Range</option>
						</select>
						<select name="month" id="month" style="display:none; margin-top:10px;font-size:12px;" class="form-control">
							<option value="01">Januari</option>
							<option value="02">Pebruari</option>
							<option value="03">Maret</option>
							<option value="04">April</option>
							<option value="05">Mei</option>
							<option value="06">Juni</option>
							<option value="07">Juli</option>
							<option value="08">Agustus</option>
							<option value="09">September</option>
							<option value="10">Oktober</option>
							<option value="11">November</option>
							<option value="12">Desember</option>
						</select>
						<br/>
						<div class="range" style="display:none; margin-top:10px;font-size:12px;" >
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>Start Date</label>
										<input style="font-size:12px;" class="datepicker" type="text" name="start_date" class="form-control" id="deposit_start">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>End Date</label>
										<input style="font-size:12px;" class="datepicker2" type="text" name="end_date" class="form-control" id="end_start">
									</div>
								</div>
							</div>
							<br/>
						</div>
					</div>
					<br/>
					<br/>
					<hr/>
					<div class="col-12">
						<div id="chartContainer" style="height: 300px; width: 100%;"></div>
					</div>
				</div>
			</div>
	</div>
</body>
<?php
include('footer.php');
?>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script>
$('.datepicker').datepicker({
	uiLibrary: 'bootstrap5',
	format: 'yyyy-mm-dd',
	header: true, 
	modal: true, 
	footer: true
});

$('.datepicker2').datepicker({
	uiLibrary: 'bootstrap5',
	format: 'yyyy-mm-dd',
	header: true, 
	modal: true, 
	footer: true
});

$('.datepicker3').datepicker({
	uiLibrary: 'bootstrap5',
	format: 'yyyy-mm-dd',
	header: true, 
	modal: true, 
	footer: true
});

$('.datepicker4').datepicker({
	uiLibrary: 'bootstrap5',
	format: 'yyyy-mm-dd',
	header: true, 
	modal: true, 
	footer: true
});

function depositchange(v){
	if(v=='month'){
		$('#month').show();
		$('.range').hide();
	}else if(v=='daterange'){
		$('#month').hide();
		$('.range').show();
	}
}


function memberchange(v){
	if(v=='month'){
		$('#month1').show();
		$('.range1').hide();
	}else if(v=='daterange'){
		$('#month1').hide();
		$('.range1').show();
	}
}
window.onload = function () {
    	var chart1 = new CanvasJS.Chart("chartContainer", {
        title: {
            text: "Deposit & Energy Deposit Statistic"
        },
        axisY: [{
            title: "Deposit",
            lineColor: "#C24642",
            tickColor: "#C24642",
            labelFontColor: "#C24642",
            titleFontColor: "#C24642",
            includeZero: true,
            suffix: "k"
        },
        {
            title: "Energy Deposit",
            lineColor: "#369EAD",
            tickColor: "#369EAD",
            labelFontColor: "#369EAD",
            titleFontColor: "#369EAD",
            includeZero: true,
            suffix: "k"
        }],
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [{
            type: "line",
            name: "Deposit",
            color: "#C24642",
            showInLegend: true,
            axisYIndex: 0,
            dataPoints: [
                { x: new Date(2017, 00, 7), y: 32.3 }, 
                { x: new Date(2017, 00, 14), y: 33.9 },
                { x: new Date(2017, 00, 21), y: 26.0 },
                { x: new Date(2017, 00, 28), y: 15.8 },
                { x: new Date(2017, 01, 4), y: 18.6 },
                { x: new Date(2017, 01, 11), y: 34.6 },
                { x: new Date(2017, 01, 18), y: 37.7 },
                { x: new Date(2017, 01, 25), y: 24.7 },
                { x: new Date(2017, 02, 4), y: 35.9 },
                { x: new Date(2017, 02, 11), y: 12.8 },
                { x: new Date(2017, 02, 18), y: 38.1 },
                { x: new Date(2017, 02, 25), y: 42.4 }
            ]
        },
        {
            type: "line",
            name: "Energy Deposit",
            color: "#369EAD",
            showInLegend: true,
            axisYIndex: 1,
            dataPoints: [
                { x: new Date(2017, 00, 7), y: 85.4 }, 
                { x: new Date(2017, 00, 14), y: 92.7 },
                { x: new Date(2017, 00, 21), y: 64.9 },
                { x: new Date(2017, 00, 28), y: 58.0 },
                { x: new Date(2017, 01, 4), y: 63.4 },
                { x: new Date(2017, 01, 11), y: 69.9 },
                { x: new Date(2017, 01, 18), y: 88.9 },
                { x: new Date(2017, 01, 25), y: 66.3 },
                { x: new Date(2017, 02, 4), y: 82.7 },
                { x: new Date(2017, 02, 11), y: 60.2 },
                { x: new Date(2017, 02, 18), y: 87.3 },
                { x: new Date(2017, 02, 25), y: 98.5 }
            ]
        }]
    });

    chart1.render();

    var chart2 = new CanvasJS.Chart("chartContainer2", {
        title: {
            text: "Member Statistic"
        },
        axisY: [{
            title: "Statistics",
            lineColor: "#C24642",
            tickColor: "#C24642",
            labelFontColor: "#C24642",
            titleFontColor: "#C24642",
            includeZero: true,
            suffix: "k"
        }],
        toolTip: {
            shared: true
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [{
            type: "line",
            name: "Members",
            color: "#C24642",
            showInLegend: true,
            axisYIndex: 0,
            dataPoints: [
                { x: new Date(2017, 00, 7), y: 32.3 }, 
                { x: new Date(2017, 00, 14), y: 33.9 },
                { x: new Date(2017, 00, 21), y: 26.0 },
                { x: new Date(2017, 00, 28), y: 15.8 },
                { x: new Date(2017, 01, 4), y: 18.6 },
                { x: new Date(2017, 01, 11), y: 34.6 },
                { x: new Date(2017, 01, 18), y: 37.7 },
                { x: new Date(2017, 01, 25), y: 24.7 },
                { x: new Date(2017, 02, 4), y: 35.9 },
                { x: new Date(2017, 02, 11), y: 12.8 },
                { x: new Date(2017, 02, 18), y: 38.1 },
                { x: new Date(2017, 02, 25), y: 42.4 }
            ]
        }]
    });
    chart2.render();

    function toggleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        e.chart.render();
    }
}

</script>