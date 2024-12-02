
<?php 
session_start();
$title = "ADMIN - GEONOLOGY MEMBER ";
include('header.php');
include('api.php');
include('connection.php');
include('function.php');
if(!isset($_SESSION['tokenadmin']) || $_SESSION['tokenadmin']==''){
	header('location:login.php');
}
if(isset($_GET['username'])){
    $check = getdata('member',$_GET['username'],'username');
    if(!$check){
        header('location:admin-geonology.php?error=notfound');
    }
    $upline = "'".$check['ref_code']."'";
    $username=$_GET['username'];
}else{
    $upline=null;
    $username='';
}
?>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
            color: #333;
        }

        .tree {
            padding: 20px;
            text-align: left;
            max-width: 1000px;
            margin: auto;
        }

        .tree ul {
            list-style-type: none;
            padding-left: 20px;
            position: relative;
        }

        .tree li {
            margin: 0;
            padding: 10px 0 0 20px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .tree li:hover {
            background-color: #eef2f7;
            transform: scale(1.02);
        }

        .tree li::before, .tree li::after {
            content: '';
            position: absolute;
            left: -20px;
        }

        .tree li::before {
            border-top: 1px solid #ccc;
            top: 20px;
            width: 20px;
            height: 0;
        }

        .tree li::after {
            border-left: 1px solid #ccc;
            height: 100%;
            width: 0;
            top: 0;
        }

        .tree li:last-child::after {
            height: 20px;
        }

        .tree li span {
            display: inline-block;
            padding: 10px 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
            transition: all 0.3s;
        }

        .tree li .hidden {
            display: none;
        }

        .member-info {
            display: flex;
            flex-direction: column;
        }

        .username {
            font-weight: bold;
            color: #2c3e50;
        }

        .details {
            font-size: 0.9em;
            color: #7f8c8d;
        }

    </style>
<body>
<?php include('menu.php');?>
	<br/>
	
	<div class="container-fluid">
		<nav aria-label="breadcrumb">
  			<ol class="breadcrumb">
    			<li class="breadcrumb-item" aria-current="page"><a href="index.php">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Geonology</li>
  			</ol>
		</nav>
		<div class="card">
  			<div class="card-body">
    			<h5>Geonology</h5>
				<br/>
                <?php 
                    if(isset($_GET['error'])){
                        ?>
                        <div class="alert alert-danger" role="alert">User Not Found!!!</div>
                        <?php
                    }
                ?>
                <form method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Username" aria-label="Recipient's username" aria-describedby="basic-addon2" name="username" value="<?php echo $username;?>">
                        <div class="input-group-append">
                            <button class="input-group-text" id="basic-addon2">Cari</button>
                        </div>
                    </div>
                </form>
				<div id="genealogyTree" class="tree"></div>
  			</div>
		</div>
	</div>
    <script>
        function fetchAndBuildTree(parentId, container) {
            fetch('fetch_data.php?parent_id=' + (parentId || ''))
                .then(response => response.json())
                .then(data => {
                    const ul = document.createElement('ul');

                    data.forEach(member => {
                        const li = document.createElement('li');
                        li.textContent = member.username + " ( Total Direct Member : " + member.downline_count + " | Turnover : "+member.turnover+" | Total Buying Energi : "+member.total_balance_in+" | Personaltrade : "+member.personaltrade+"| Teamstrade : "+member.teamstrade+")";
                        li.dataset.id = member.ref_code;
                        li.appendChild(buildPlaceholder());
                        li.addEventListener('click', handleNodeClick);
                        ul.appendChild(li);
                    });

                    container.appendChild(ul);
                })
                .catch(error => console.error('Error:', error));
        }

        function buildPlaceholder() {
            const span = document.createElement('span');
            span.classList.add('hidden');
            return span;
        }

        function handleNodeClick(event) {
            event.stopPropagation(); // Prevent event from bubbling up
            const li = event.currentTarget;
            const span = li.querySelector('span');

            if (span.classList.contains('hidden')) {
                span.classList.remove('hidden');
                fetchAndBuildTree(li.dataset.id, span);
            } else {
                span.classList.add('hidden');
                span.innerHTML = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const treeContainer = document.getElementById('genealogyTree');
            <?php if($upline!=null){?>
                fetchAndBuildTree(<?php echo $upline;?>, treeContainer);
            <?php }else{
                ?>
                fetchAndBuildTree(null, treeContainer);
                <?php
            }?>
        });
    </script>
    <?php include('footer.php');?>
</body>
</html>
