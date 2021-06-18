<?php 
    require_once "config.php";
	session_start();

	//initialize variables
	$title = "";
	$details = "";

    $student_id = $_SESSION['std_id'];
	$title = $_POST['title'];
    $details = $_POST['details'];

    if(!isset($_SESSION['std_id'])) {
        $_SESSION['error_msg'] = "Please, login to ask questions !!";
        header("location: ask_question.php");            
    } else {            

        $sql = "INSERT INTO questions (std_id , title, description, published_date) VALUES (?, ?, ?, ?)";
                    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $param_std_id, $param_title, $param_desc, $param_published_date);
            
            // Set parameters
            $param_std_id = $student_id;
            $param_title = $title;
            $param_desc = nl2br($details);
            $param_published_date = date('Y-m-d H:i:s A');
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                $_SESSION['success_msg'] = "Your question successfully submitted..";
                header("location: ask_question.php");
            } else{
                $_SESSION['error_msg'] = "Please, submit again !!";
                header("location: ask_question.php");
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }

?>