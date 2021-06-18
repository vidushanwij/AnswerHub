<?php
    session_start();
    require_once "config.php";

    if (isset($_POST['login'])) {
		$uname = $_POST['username'];
		$pwd = $_POST['password'];

        //Check whether user already exists
        $sql="select count(*) as total from employers where username='".$uname."'";
        $result=mysqli_query($link,$sql);
        $data=mysqli_fetch_assoc($result);

        if($data['total'] > 0) {

            $sql="select * from employers where username='".$uname."'";
            $result=mysqli_query($link,$sql);
            $data=mysqli_fetch_assoc($result);
            
            if($data['password'] == $pwd) {
                $_SESSION['emp_id'] = $data['id'];
                $_SESSION['emp_name'] = $data['username'];
                $_SESSION['img_name'] = $data['profile_img'];

                header("location: employer_dashboard/dashboard.php");
            } else {
                $_SESSION['error_msg'] = "Password is incorrect !!";
                header("location: emp_login.php");
            }

        } else {
            $_SESSION['error_msg'] = "User does not exist !!";
            header("location: emp_login.php");
        }       
	}
?>