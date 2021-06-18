<?php 
    require_once "config.php";
	session_start();

	//initialize variables
	$details = "";
    $question_id = "";
    $newfilename = "";

    $student_id = $_SESSION['std_id'];
    $question_id = $_POST['question_id'];
    $details = $_POST['desc'];

    if(!isset($_SESSION['std_id'])) {
        $_SESSION['error_msg'] = "Please, login to answer questions !!";
        header("location: view_question.php?id=".$question_id);            
    } else {  
        
        //Check whether answer already exists
        $sql="select count(*) as total from answers where std_id='".$student_id."' and question_id = '".$question_id."'";
        $result=mysqli_query($link,$sql);
        $data=mysqli_fetch_assoc($result);

        if($data['total'] == 0) {

            if($_FILES["file_upload"]["name"] != "") {
                $filename = $_FILES["file_upload"]["name"];
                $tempname = $_FILES["file_upload"]["tmp_name"];   
                $newfilename = rand(1, 100).'_'.$filename; 
                $folder = "answer_uploads/".$newfilename;
                move_uploaded_file($tempname, $folder);
            }     
            
            $sql = "INSERT INTO answers(std_id, question_id, answer, file_upload, answered_date, plus_votes, minus_votes) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "iisssii", $param_std_id, $param_question_id, $param_answer, $param_file_name, $param_answered_date, $param_plus_votes, $param_minus_votes);
                
                // Set parameters
                $param_std_id = $student_id;
                $param_question_id = $question_id;
                $param_answer = nl2br($details);
                $param_file_name = $newfilename;
                $param_answered_date = date('Y-m-d H:i:s A');
                $param_plus_votes = 0;
                $param_minus_votes = 0;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    $_SESSION['success_msg'] = "Your answer successfully submitted..";
                    header("location: view_question.php?id=".$question_id);
                } else{
                    $_SESSION['error_msg'] = "Please, submit your answer again !!";
                    header("location: view_question.php?id=".$question_id);
                }
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
    
            // Close connection
            mysqli_close($link);
        } else {
            $_SESSION['error_msg'] = "You have alreay answered this question !!";
            header("location: view_question.php?id=".$question_id);
        }        
    }

?>