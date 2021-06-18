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
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
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
</head>
<body>
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
        <a class="navbar-brand" href="index.php" style="width: 20%;">
            <img class="d-inline-block align-top" alt="" src="images/logo.png" style="width: 40%;">
        </a>
        <div class="paper_menu">
            <div id="dl-menu" class="xv-menuwrapper responsive-menu">
                <ul class="dl-menu align-items-center">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="vacancies.php">Careers</a></li>
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
    <section class="search-section home-search">
        <div class="masthead text-center">
            <div class="container" style="margin-bottom: 10%;">
                <div class="row">
                    <div class="col-lg-8 mx-md-auto">
                        <h1>Answer Hub</h1>
                        <p class="lead text-muted">
                            A platform where students can clear their doubts on subject matters..
                        </p>
                        <form>
                            <input type="text" class="search-field" placeholder="Search Something ... ">
                            <button type="submit"><i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Topics -->
    <section class="topics">
        <div class="container">
            <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8 col-md-6">
                    <div class="topics-wrapper border-style">
                        <h3><span class="fa fa-circle-o text-blue"></span>&nbsp;Recently asked Questions</h3>
                        <ul class="topics-list">
                            <?php
                                // Include config file
                                require_once "config.php";
                                
                                $cur_date = date('Y-m-d');

                                // Attempt select query execution
                                $sql = "SELECT *  FROM questions ORDER BY published_date DESC";
                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        $i = 0;
                                        while($row = mysqli_fetch_array($result)){
                                            $i++;
                            ?>
                            <li><a href="view_question.php?id=<?php echo $row['id'] ?>"><?php echo $row['title'] ?></a>
                            </li>

                            <?php 
                                        }
                                        mysqli_free_result($result);
                                    }
                                }
                            ?>
                        </ul>
                        <ul class="topics-meta">
                            <li style="font-weight: 600;font-size: 20px;"><?php echo (isset($i)) ? $i : '0' ?> Questions</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2"></div>
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

</body>
</html>