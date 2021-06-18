<?php
    session_start();
    require_once "config.php";

    if (isset($_POST['login'])) {
		$uname = $_POST['username'];
		$pwd = $_POST['password'];

        //Check whether user already exists
        $sql="select count(*) as total from students where username='".$uname."'";
        $result=mysqli_query($link,$sql);
        $data=mysqli_fetch_assoc($result);

        if($data['total'] > 0) {

            $sql="select * from students where username='".$uname."'";
            $result=mysqli_query($link,$sql);
            $data=mysqli_fetch_assoc($result);
            
            if($data['pass'] == $pwd) {
                $_SESSION['std_id'] = $data['id'];
                $_SESSION['std_name'] = $data['username'];
                $_SESSION['img_name'] = $data['profile_img'];

                header("location: index.php");
            } else {
                $_SESSION['error_msg'] = "Password is incorrect !!";
                header("location: login.php");
            }

        } else {
            $_SESSION['error_msg'] = "User does not exist !!";
            header("location: login.php");
        }       
	}
?>