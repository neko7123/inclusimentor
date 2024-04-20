<?php
session_start();
include("connection.php");
include("function.php");

if($_SERVER['REQUEST_METHOD']== "POST")
{
    //something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!empty($username) && !empty($password))
    {
        //read database
        
        $query = "select * from mentor where username = '$username' limit 1";
        
        $result = mysqli_query($con, $query);

        if($result)
        {
            if($result && mysqli_num_rows($result) > 0)
            {
                $mentor_data = mysqli_fetch_assoc($result);
                
                if($mentor_data['password'] == $password)
                {
                    $_SESSION['mentor_id'] = $mentor_data['mentor_id'];
                    header("Location: profile_mentor.php");
                    die;
                }
            }
            echo "Wrong username or password";
        }else
        {
            echo "Wrong username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
        <div id="title">Login to InclusiMentor</div><br>
        <div id="links">
            <a href="index.php#skills" class="nav-link">About Us</a>
            <a href="index.php#work" class="nav-link" style="margin:0px 60px;">Why Us?</a>
            <a href="signup.php" class="nav-link">Sign Up</a>
        </div>
    </div>
    <div></div>
    <div></div>
    <center>
        <div id="middle">
            <div id="tagline" class="animated zoomIn" style="animation-delay:1.8s;">
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required class="form-input">
                    <input type="password" name="password" placeholder="Password" required class="form-input"><br>
                    <input type="submit" value = "Login" class="btn_one">
                </form>
            </div>
        </div>
    </center>

    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
    </div>

    <script src="index.js" type="text/javascript"></script>
</body>
</html>
