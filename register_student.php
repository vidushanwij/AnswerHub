<?php 
    require_once "config.php";
	session_start();

	//initialize variables
	$uname = "";
    $email = "";
    $pass = "";
    $conpass = "";

	if (isset($_POST['save'])) {
		$uname = $_POST['uname'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $conpass = $_POST['conpassword'];

        if($conpass != $pass) {
            $_SESSION['error_msg'] = "Password confirmation does not match !!";
            header("location: student_register.php");
        } else {
            //Check whether user already exists
            $sql="select count(*) as total from students where username='".$uname."'";
            $result=mysqli_query($link,$sql);
            $data=mysqli_fetch_assoc($result);

            if($data['total'] == 0) {
                // Prepare an insert statement
                $sql = "INSERT INTO students (username, email, pass) VALUES (?, ?, ?)";
                                        
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "sss", $param_uname, $param_email, $param_pass);
                    
                    // Set parameters
                    $param_uname = $uname;
                    $param_email = $email;
                    $param_pass = $pass;
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Records created successfully. Redirect to landing page
                        $_SESSION['success_msg'] = "Successfully registered. Please, login to continue..";
                        header("location: student_register.php");
                    } else{
                        $_SESSION['error_msg'] = "Registration failed !!";
                        header("location: student_register.php");
                    }
                } 

                // Close statement
                mysqli_stmt_close($stmt);

                // Close connection
                mysqli_close($link);
            } else {
                $_SESSION['error_msg'] = "User already exists. Please, use a different username !!";
                header("location: student_register.php");
            }            
        }     
	}

?>