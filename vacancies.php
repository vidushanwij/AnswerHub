<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Answer Hub</title>
    <link rel="shortcut icon" href="images/mini-logo.png">
    <!-- CSS -->
    <link rel="stylesheet" href="css/app.css">    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
    <style>
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F5F8FA;
            z-index: 9998;
            text-align: center;
        }

        .plane-container {
            position: absolute;
            top: 50%;
            left: 50%;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js'></script> 
</head>
<body>
    <?php
        if(isset($_SESSION['success_msg'])) {
            echo "
                <script type=\"text/javascript\">
                    $.notify('".$_SESSION['success_msg']."', {
                    position:'top right',
                    className: 'success',
                    autoHideDelay: 7000
                    });
                </script>
            ";
            unset($_SESSION['success_msg']);
        } 
    ?>

    <?php
        if(isset($_SESSION['error_msg'])) {
            echo "
                <script type=\"text/javascript\">
                    $.notify('".$_SESSION['error_msg']."', {
                    position:'top right',
                    className: 'error',
                    autoHideDelay: 7000
                    });
                </script>
            ";
            unset($_SESSION['error_msg']);
        } 
    ?>

<!-- Pre loader -->
<div id="loader" class="loader">
    <div class="plane-container">
        <div class="l-s-2 blink">LOADING</div>
    </div>
</div>

<div id="app" class="paper-loading">
<!-- Header -->
<nav class="mainnav navbar navbar-default justify-content-between">
    <div class="container relative">
        <a class="offcanvas dl-trigger paper-nav-toggle" data-toggle="offcanvas"
           aria-expanded="false" aria-label="Toggle navigation">
            <i></i>
        </a>
        <a class="navbar-brand" href="index.php">
            <img class="d-inline-block align-top" alt="" src="images/logo.png" style="width: 40%;">
        </a>
        <div class="paper_menu">
            <div id="dl-menu" class="xv-menuwrapper responsive-menu">
                <ul class="dl-menu align-items-center">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="vacancies.php" class="active">Careers</a></li>
                    <li><a href="ask_question.php">Ask a Question</a></li>
                    <?php if(isset($_SESSION['std_id'])) { ?>
                        <li><a href="student_dashboard/dashboard.php">Dashboard</a></li>
                        <li><a href="student_dashboard/logout.php?id=<?php echo $_SESSION['std_id'] ?>"><?php echo ($_SESSION['img_name'] == '') ? '<img style="border-radius: 50%;" width="80" height="80" src="user_images/user_default.png">' : '<img style="border-radius: 50%;" width="80" height="80" src="user_images/'.$_SESSION['img_name'].'">' ?> &nbsp;Logout</a></li>
                    <?php } else{ ?>
                        <li><a href="login.php" class="btn btn-default nav-btn">Login</a></li>
                    <?php
                        }
                    ?>                    
                </ul>
            </div>
<!-- Login modal -->
        </div>
    </div>
</nav>
<main>
    <!-- Search Section -->
    <section class="search-section home-search vac_bg">
        <div class="masthead text-center" id="vacs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-md-auto">
                        <h1 style="margin-bottom: 20%;"></h1>
                    </div>
                </div>
            </div>
        </div>
    </section>  
    
    <section class="topics">
        <div class="container">
            <div class="row">

                <?php
                    // Include config file
                    require_once "config.php";                    
                    $cur_date = date('Y-m-d');

                    // Attempt select query execution
                    $sql = "";
                    if(!isset($_SESSION['std_id'])) {
                        $sql = "SELECT v.*, e.company  FROM vacancies v INNER JOIN employers e ON v.emp_id = e.id  where v.exp_date >= '".$cur_date."' ORDER BY v.published_date DESC";
                    } else {
                        $sql1="select * from students where id  = '".$_SESSION['std_id']."'";
                        $result=mysqli_query($link,$sql1);
                        $data=mysqli_fetch_assoc($result);
                        $faculty = $data['faculty'];
                        $field = $data['preferred_field'];
                        $posAtt = $data['pos_attitude'];
                        $comSkills = $data['com_skills'];
                        $teamwork = $data['team_work'];
                        $selfMgt = $data['self_mgt'];
                        $will = $data['will_learn'];
                        $critical = $data['critical_thinking'];
                        $res = $data['resilience'];

                        if($faculty == "" && $field == "") {
                            $sql = "SELECT v.*, e.company  FROM vacancies v INNER JOIN employers e ON v.emp_id = e.id  where v.exp_date >= '".$cur_date."' ORDER BY v.published_date DESC";
                        } else {
                            $sql = "SELECT v.*, e.company FROM vacancies v INNER JOIN employers e ON v.emp_id = e.id  where v.exp_date >= '".$cur_date."' AND v.faculties LIKE '%".$faculty."%' AND v.fields LIKE '%".$field."%'
                            AND (v.pos_attitude ='".$posAtt."' OR v.com_skills='".$comSkills."' OR v.team_work='".$teamwork."' OR v.self_mgt='".$selfMgt."' OR v.will_learn='".$will."' OR v.critical_thinking='".$critical."' OR v.resilience='".$res."') ORDER BY v.published_date DESC";
                        }                        
                    }          
                    
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            $i = 0;?>

                            <?php 
                                if(isset($_SESSION['std_id'])) { ?>
                                <div class="col-md-12">
                                    <h3>Suggested for you</h3>
                                </div>
                            <?php
                                }
                            ?>

                <?php
                            while($row = mysqli_fetch_array($result)){
                                $i++;
                ?>                

                <div class="col-md-4">
                    <div class="card" style="width:20rem; margin-top: 5%;">
                        <img class="card-img-top" src="vacancy_images/<?php echo $row['img_name'] ?>" alt="Card image" style="width:100%; height: 250px;">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $row['title'] ?></h4>
                            <p class="card-text" style="font-size: 18px;font-weight: 500;"><?php echo $row['company'] ?></p>
                            <p class="card-text" style="font-size: 16px;font-weight: 500;color: grey;">
                                Opening Date: <?php echo $row['published_date'] ?><br>
                                Closing Date: <?php echo $row['exp_date'] ?>
                            </p>
                            <a href="#myModal<?php echo $i ?>" data-toggle="modal" class="btn btn-primary">Show Info</a>&nbsp;&nbsp;
                            <a href="#applyModal<?php echo $i ?>" data-toggle="modal" class="btn btn-success">Apply Now</a>
                        </div>
                    </div>
                </div>

                <!-- Info Modal -->
                <div class="modal fade" id="myModal<?php echo $i ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title" style="color: #fff;"><?php echo $row['title'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6" style="font-weight: 500;">Opening Date: <?php echo $row['published_date'] ?></div>
                                    <div class="col-md-6" style="font-weight: 500;">Closing Date: <?php echo $row['exp_date'] ?></div>
                                    <div class="col-md-6" style="font-weight: 500;">Company: <?php echo $row['company'] ?></div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img style="width: 100%;" src="vacancy_images/<?php echo $row['img_name'] ?>">
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo $row['description'] ?>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6>Key Skills required</h6>
                                        <ul>
                                            <?php echo ($row['pos_attitude'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Positive Attitude (Being calm and cheerful when things wrong)</li>' : '' ?>
                                            <?php echo ($row['com_skills'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Communication Skills</li>' : '' ?>
                                            <?php echo ($row['team_work'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Teamwork (You help out when it gets busy at work)</li>' : '' ?>
                                            <?php echo ($row['self_mgt'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Self Management</li>' : '' ?>
                                            <?php echo ($row['will_learn'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Willingness to learn</li>' : '' ?>
                                            <?php echo ($row['critical_thinking'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Critical thinking (Problem solving and decision making)</li>' : '' ?>
                                            <?php echo ($row['resilience'] == "Y") ? '<li style="list-style-type: circle;margin-left:5%">Resilience</li>' : '' ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apply Modal -->
                <div class="modal fade" id="applyModal<?php echo $i ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-success">
                                <h5 class="modal-title" style="color: #fff;">Upload CV</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="apply_vacancy.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input id="fileUpload" name="fileUpload" type="file" accept="application/pdf, application/msword" required>
                                                <input type="hidden" name="vacancy_id" id="vacancy_id" value="<?php echo $row['id']  ?>">
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="modal-footer">
                                    <button type="submit"  name="apply" class="btn btn-success">Apply</button>                                
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>                            
                        </div>
                    </div>
                </div>

                <?php 
                            }
                            mysqli_free_result($result);
                        }
                    }
                ?>
            </div>
        </div>        
    </section>
</main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4 responsive-phone"><a href="index.php" class="brand">
                    <img src="images/logo.png" alt="Logo">
                </a>
                </div>
                <div class="col-md-4">
                    <h6>Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a>
                        </li>
                        <li><a href="vacancies.php">Careers</a>
                        </li>
                        <li><a href="ask_question.php">Ask a Question</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </footer>
</div>
<!--End Page page_wrrapper -->
    <script src="js/app.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>