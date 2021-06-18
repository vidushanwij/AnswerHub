<?php    
    session_start();
    require_once "config.php";

    $vac_id = "";
    $file_name = "";

    if(isset($_SESSION['std_id'])) {
        $vac_id = $_POST['vacancy_id'];

        $filename = $_FILES["fileUpload"]["name"];
        $tempname = $_FILES["fileUpload"]["tmp_name"];   
        $newfilename = rand(1, 1000).'_'.$filename; 
        $folder = "cv_files/".$newfilename;
        $file_name = $newfilename;

        if (move_uploaded_file($tempname, $folder))  { 
            // Prepare an insert statement
            $sql = "INSERT INTO applicants (job_id, std_id, file_name, uploaded_date) VALUES (?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "iiss", $param_job_id, $param_std_id, $param_file_name, $param_uploaded_date);
                
                // Set parameters
                $param_job_id = $vac_id;
                $param_std_id = $_SESSION['std_id'];
                $param_file_name = $file_name;
                $param_uploaded_date = date('Y-m-d');
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records created successfully. Redirect to landing page
                    $_SESSION['success_msg'] = "You have successfully submiited your application..";
                    header("location: vacancies.php");
                } else{
                    $_SESSION['error_msg'] = "Oops!! Something went wrong. Please, try again later !!";
                    header("location: vacancies.php");
                }
            }
            
            // Close statement
            mysqli_stmt_close($stmt);

            // Close connection
            mysqli_close($link); 
        } else {
            $_SESSION['error_msg'] = "Image upload failed..";
            header("location: vacancies.php");
        }

    } else {
        $_SESSION['error_msg'] = "Please, login to your account to apply for vacancies !!";
        header("location: vacancies.php");
    }
?>