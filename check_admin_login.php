<?php
    session_start();
    require_once "config.php";

    if (isset($_POST['login'])) {
		$uname = $_POST['username'];
		$pwd = $_POST['password'];

        //Check whether user already exists
        $sql="select count(*) as total from admins where uname='".$uname."'";
        $result=mysqli_query($link,$sql);
        $data=mysqli_fetch_assoc($result);

        if($data['total'] > 0) {

            $sql="select * from admins where uname='".$uname."'";
            $result=mysqli_query($link,$sql);
            $data=mysqli_fetch_assoc($result);
            
            if($data['password'] == $pwd) {
                $_SESSION['admin_id'] = $data['id'];
                $_SESSION['admin_name'] = $data['uname'];

                header("location: admin_dashboard/dashboard.php");
            } else {
                $_SESSION['error_msg'] = "Password is incorrect !!";
                header("location: admin_login.php");
            }

        } else {
            $_SESSION['error_msg'] = "User does not exist !!";
            header("location: admin_login.php");
        }       
	}
?>