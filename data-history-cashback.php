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
        $sql = "SELECT a.username,b.* FROM account_balance_histories b JOIN member a ON a.id=b.user_id WHERE (b.decription LIKE '%cashback%' OR b.decription LIKE '%Cashback%')LIMIT :limit OFFSET :offset ";
    }else{
        $sql = "SELECT a.username,b.* FROM account_balance_histories b JOIN member a ON a.id=b.user_id WHERE a.username LIKE '%$searchValue%' AND (b.decription LIKE '%cashback%' OR b.decription LIKE '%Cashback%') ";
    }

    // Execute the query
    $stmt = $conn->prepare($sql);
    //$searchTerm = "%" . $searchValue . "%";
    //$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

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
    
    
    // Get the total number of records
    
    $totalRecordsQuery = "SELECT COUNT(*) FROM account_balance_histories b JOIN member a ON a.id=b.user_id  WHERE (b.decription LIKE '%cashback%' OR b.decription LIKE '%Cashback%')";
   
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

