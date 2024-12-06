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
				<div class="row">
					<div class="col-6">
						<h6>Account Deposit</h6>
					</div>
					<div class="col-12">
						<table width="100%" class="table" style="font-size:12px;">
							<thead class="thead-dark">
								<tr>
									<th scope="col">#ID</th>
									<th scope="col">User ID</th>
									<th scope="col">Created Date</th>
									<th scope="col">Amount</th>
									<th scope="col">Status</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-6">
						<h6>Package</h6>
					</div>
					<div class="col-12">
						<table width="100%" class="table" style="font-size:12px;">
							<thead class="thead-dark">
								<tr>
									<th scope="col">#ID</th>
									<th scope="col">Package Name</th>
									<th scope="col">Package</th>
									<th scope="col">Profit Setting</th>
									<th scope="col">Created At</th>
									<th scope="col">Updated At</th>
									<th scope="col">Action</th>
								</tr>
								<?php 
									$results = getenergypackages();
									foreach($results as $item){
										?>
										<tr>
											<td scope="col"><?php echo $item['id'];?></td>
											<td scope="col"><?php echo $item['package_name'];?></td>
											<td scope="col"><?php echo $item['price'];?></td>
											<td scope="col"><?php echo $item['getpoint'];?>%</td>
											<td scope="col"><?php echo $item['created_date'];?></td>
											<td scope="col"><?php echo $item['updated_date'];?></td>
											<td scope="col"><a href="edit-profit.php?id=<?php echo $item['id'];?>">Edit Profit</a></td>
										</tr>
										<?php
									}
								?>
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