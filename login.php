<?php 
    session_start();
    include('api.php');
    $valid = false;
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if($username!='' && $password!=''){
            $data = array(
                'username'=>$username,
                'password'=>$password
            );
            $response = postAPI('loginadmin',$data);
            if($response['error']==0){
                $_SESSION['tokenadmin']=$response['token'];
                $result = _postApiLogin('getuserinfoadmin',$_SESSION['tokenadmin']);
                if($result['data']['username']){
                    $_SESSION['usernameadmin']=$result['data']['username'];
                    $_SESSION['role']=$result['data']['role'];
                    header('location:index.php');
                }else{
                    $response = array(
                        'error'=>1,
                        'message'=>'Invalid username or password'
                    );
                }
            }
            $valid = true;
        }else{
            $response = array(
                'error'=>1,
                'message'=>'Invalid username or password'
            );
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login to Admin Panel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3>Admin Login</h3>
        </div>
        <form method="post">
            <div style="padding:10px;">
                <?php if($valid){?>
                <?php 
                    if($response['error']==1){
                        $alert = 'alert-danger';
                    }else{
                        $alert='alert-success';
                    }
                ?>
                <div class="alert <?php echo $alert;?>" role="alert">
                    <?php echo $response['message'];?>
                </div>
                <?php }?>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control input-shadow" name="username" id="username" placeholder="Enter username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control input-shadow" name="password" id="password" placeholder="Enter password" required>
            </div>
            <div class="d-grid">
                <button class="btn btn-primary text-uppercase py-2" name="submit" type="submit">Login</button>
            </div>
        </form>
        
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
