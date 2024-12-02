<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include('connection.php');

try {
    // Create connection to PostgreSQL database
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	if(isset($_POST['start'])){
    	// Get the request parameters
    	$draw = $_POST['draw'];
    	$limit = intval($_POST['length']);
    	$offset = intval($_POST['start']);
    	$searchValue = $_POST['search']['value'];
    }else{
    	$draw = 1;
    	$limit = 100;
    	$offset = 0;
    	$searchValue = '';
    }

    if(isset($_POST['user_star'])){
        $star = $_POST['user_star'];
    }else{
        $star=1;
    }
	$offest = $offset*$limit;

    // Prepare the base SQL query with limit and offset for pagination
    if($searchValue==''){
        if($star>1){
            $sql = "SELECT id,username,email,upline_id,country,personaltrade,teamstrade,turnover FROM member WHERE user_star=:star ORDER BY created_date DESC LIMIT :limit OFFSET :offset ";
        }else{
            $sql = "SELECT id,username,email,upline_id,country,personaltrade,teamstrade,turnover FROM member WHERE user_star>:star ORDER BY created_date DESC LIMIT :limit OFFSET :offset ";
        }
    }else{
        $sql = "SELECT id,username,email,upline_id,country,personaltrade,teamstrade,turnover FROM member WHERE user_star>0 AND (username LIKE '%$searchValue%' OR email lIKE '%$searchValue%') ORDER BY created_date DESC LIMIT :limit OFFSET :offset ";
    }

    // Execute the query
    $stmt = $conn->prepare($sql);
    //$searchTerm = "%" . $searchValue . "%";
    //$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    if($searchValue==''){
        $stmt->bindParam(':star', $star, PDO::PARAM_INT);
    }
    
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
	// Get the current timestamp
	$currentTimestamp = time();

	// Subtract 7 days (7 * 24 * 60 * 60 seconds)
	$sevenDaysAgoTimestamp = $currentTimestamp - (7 * 24 * 60 * 60);

	// Format the timestamp as a date
	$sevenDaysAgo = date('Y-m-d', $sevenDaysAgoTimestamp);

    // Fetch the results
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    	foreach ($members as &$member) {
        	$member['actions']='<a href="detail-user.php?id='.$member['id'].'" class="btn btn-primary">View</a>';
    	}
    
    // Get the total number of records
    if($star>1){
        $totalRecordsQuery = "SELECT COUNT(*) FROM member WHERE user_star='$star'";
    }else{
        $totalRecordsQuery = "SELECT COUNT(*) FROM member WHERE user_star>'$star'";
    }
    $totalRecordsResult = $conn->query($totalRecordsQuery);
    $totalRecords = $totalRecordsResult->fetchColumn();

    //$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $totalFilteredRecords = $stmt->fetchColumn();

    // Prepare the response for DataTables
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => intval(count($members)),
        "recordsFiltered" => intval(count($members)),
        "data" => $members,
    	"defaults"=>[]
    );

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

