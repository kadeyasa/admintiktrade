<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");
// Allow specified methods
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Allow specified headers
header("Access-Control-Allow-Headers: Content-Type");


$is_local = true;
$is_mt = false;

if($is_local){
    $_url="http://localhost:8080/";
}else{
    $_url ="https://api.findtech.pro/";
}

if($is_mt){
    header("location:maintaince");
}
function getAPI($action){
	global $_url;
    // Initialize cURL session
    $curl = curl_init();

    // Set the URL to request
    $url = $_url.$action;

    // Set your API key and secret key
    $api_key = "BOT_2bf3e1e5c6df805d1507339bba38cfd09d32ceed";
    $secret_key = "5195980ef7f9f6dced0a6e71058d048fa77c7e7d8d48909ae1e72952d83ea294";

    // Set any additional options
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it
        CURLOPT_HTTPHEADER => [
            "Apikey: $api_key",
            "Secretkey: $secret_key",
        ],
        // Add more options as needed, such as CURLOPT_POST for POST requests, CURLOPT_POSTFIELDS for POST data, etc.
    ];

    // Set cURL options
    curl_setopt_array($curl, $options);

    // Execute the request and capture the response
    $response = curl_exec($curl);

    // Check for errors
    if(curl_errno($curl)){
        // Handle the error, e.g., log it or display an error message
        echo 'Curl error: ' . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Handle the response
    if ($response) {
        // Do something with the response, e.g., parse JSON or XML
        $responseData = json_decode($response, true);
        return $responseData;
    } else {
        // Handle the case where no response was received
        echo "No response received from server.";
    }
}

function postAPI($action,$post_data=array()){
	global $_url;
    // Initialize cURL session
    $curl = curl_init();

    // Set the URL to request
    $url = $_url.$action;

    // Set your API key and secret key
    $api_key = "BOT_2bf3e1e5c6df805d1507339bba38cfd09d32ceed";
    $secret_key = "5195980ef7f9f6dced0a6e71058d048fa77c7e7d8d48909ae1e72952d83ea294";

    // Set any additional options
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it
        CURLOPT_POST => true, // Set to true for a POST request
        CURLOPT_POSTFIELDS => http_build_query($post_data), // Encode the data as a query string
        CURLOPT_HTTPHEADER => [
            "Apikey: $api_key",
            "Secretkey: $secret_key",
        ],
        // Add more options as needed
    ];

    // Set cURL options
    curl_setopt_array($curl, $options);

    // Execute the request and capture the response
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        // Handle the error, e.g., log it or display an error message
        echo 'Curl error: ' . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Handle the response
    if ($response) {
        // Do something with the response, e.g., parse JSON or XML
        $responseData = json_decode($response, true);
        return $responseData;
    } else {
        // Handle the case where no response was received
        echo "No response received from server.";
    }

}

function _postAPIlogin($action,$token,$post_data=array()){
	global $_url;
	// Initialize cURL session
    $curl = curl_init();

    // Set the URL to request
    $url = $_url.$action;

    // Set your API key and secret key
    $api_key = "BOT_2bf3e1e5c6df805d1507339bba38cfd09d32ceed";
    $secret_key = "5195980ef7f9f6dced0a6e71058d048fa77c7e7d8d48909ae1e72952d83ea294";

    // Set any additional options
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true, // Return the response as a string instead of outputting it
        CURLOPT_POST => true, // Set to true for a POST request
        CURLOPT_POSTFIELDS => http_build_query($post_data), // Encode the data as a query string
        CURLOPT_HTTPHEADER => [
            "Apikey: $api_key",
            "Secretkey: $secret_key",
        	//"Content-Type: application/json",
    		"Authorization: Bearer " . $token
        ],
        // Add more options as needed
    ];

    // Set cURL options
    curl_setopt_array($curl, $options);

    // Execute the request and capture the response
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        // Handle the error, e.g., log it or display an error message
        echo 'Curl error: ' . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Handle the response
    if ($response) {
        // Do something with the response, e.g., parse JSON or XML
        $responseData = json_decode($response, true);
        return $responseData;
    } else {
        // Handle the case where no response was received
        echo "No response received from server.";
    }
}

function filter_post_value($value) {
    // Trim whitespace from the value
    $filtered_value = trim($value);
    // Remove any HTML tags
    $filtered_value = strip_tags($filtered_value);
    // Convert special characters to HTML entities to prevent XSS attacks
    $filtered_value = htmlspecialchars($filtered_value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Add slashes to the value to prevent SQL injection
    $filtered_value = addslashes($filtered_value);
    // If you're storing data in a database, consider using prepared statements instead of addslashes
    // For example: $filtered_value = $mysqli->real_escape_string($filtered_value);

    return $filtered_value;
}


function Login(){
    $email = filter_post_value($_POST['email']);
    $password = filter_post_value($_POST['password']);
    $data = array(
        'email'=>$email,
        'password'=>$password
    );
    //echo json_encode($data);
    postAPI('login',$data);
}

function register(){
    $username = filter_post_value($_POST['username']);
    $email = filter_post_value($_POST['email']);
    $password = filter_post_value($_POST['password']);
    $repassword = filter_post_value($_POST['repassword']);
    $country = filter_post_value($_POST['country']);
    $ref_code = filter_post_value($_POST['partner']);
    $data = array(
        'username'=>$username,
        'email'=>$email,
        'password'=>$password,
        'repassword'=>$repassword,
        'country'=>$country,
        'ref_code'=>$ref_code
    );
    //echo json_encode($data);
    $res = postAPI('register',$data);
	return $res;
}

function action($action){
	if($action!=''){
    	$action();
	}else{
    	$data = array(
        	'error'=>1,
        	'message'=>'Invalid action'
    	);	
	}
}

?>
