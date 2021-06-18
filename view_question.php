<?php
    session_start();
    // Include config file
    require_once "config.php";
    
    // Define variables and initialize with empty values
    $id = "";
    $student_name = "";
    $std_profile_img = "";
    $title = "";
    $description = "";
    $published_date = "";
    
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT q.*, s.username, s.profile_img FROM questions q inner join students s on q.std_id=s.id WHERE q.id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $student_name = $row['username'];
                    $std_profile_img = $row['profile_img'];
                    $title = $row['title'];
                    $description = $row['description'];
                    $published_date = $row['published_date'];
                }                 
            } 
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/mini-logo.png">
    <title>Answer Hub</title>
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
<main class="single single-knowledgebase">
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
        <li><a href="index.php">Home</a></li>
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
    <!-- Search Section -->
    <section class="search-section home-search answer_bg">
        <div class="masthead text-center" id="vacs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-md-auto">
                        <h1 style="margin-bottom: 20%;color: #fff;"></h1>
                    </div>
                </div>
            </div>
        </div>
    </section> 
    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container">
            <div class="col-lg-10 mx-md-auto ">
                <article class="post">
                    <h2 style="text-align: center;"><?php echo $title ?></h1>
                    <ul class="meta">
                        <li><span>Created Date:</span> <?php echo date("d-m-Y", strtotime($published_date)) ?></li>
                        <li><span>Created Time:</span> <?php echo date("h:i:s A", strtotime($published_date)) ?></li>
                        <li><?php echo $student_name ?></li>
                    </ul>
                    
                    <blockquote>
                        <?php echo $description ?>
                    </blockquote><br>

                    <?php 
                        //Get no.of answers
                        $sql="select count(*) as total from answers where question_id = '".$id."'";
                        $result=mysqli_query($link,$sql);
                        $data=mysqli_fetch_assoc($result);
                    ?>
                    
                    <h2><?php echo ($data['total'] == 1) ? $data['total'].' Answer' : $data['total'].' Answers' ?></h2>
                </article>
            </div>
        </div>
        <div class="container" style="margin-top: 3%;">
            <div class="col-xl-8 mx-lg-auto">
                <div class="comments">
                    <ol class="comment-list">
                        <?php 
                            if($data['total'] > 0) {                                                        
                                // Attempt select query execution
                                $sql1 = "SELECT A.*, S.username, S.profile_img from answers A INNER JOIN students S ON A.std_id = S.id WHERE question_id = '".$id."' ORDER BY A.plus_votes DESC";
                                if($result1 = mysqli_query($link, $sql1)){
                                    if(mysqli_num_rows($result1) > 0){
                                        while($row = mysqli_fetch_array($result1)){
                        ?>

                        <li style="margin-top: 10%;" id="comment-1" class="comment"><a class="float-left" href="#">
                            <img class="avatar" src="user_images/<?php echo (isset($row['profile_img'])) ? $row['profile_img'] : 'user_default.png' ?>" alt=""> </a>
                            <div class="media-body description">
                                <i onclick="upVote(<?php echo $row['id'] ?>)" class="fa fa-thumbs-up fa-lg"></i>&nbsp;&nbsp;<span style="font-size: 18px;" id="plus_vote_id<?php echo $row['id'] ?>"><?php echo $row['plus_votes'] ?></span>&nbsp;&nbsp;<i onclick="downVote(<?php echo $row['id'] ?>)" class="fa fa-thumbs-down fa-lg"></i>&nbsp;
                                <span style="font-size: 18px;" id="minus_vote_id<?php echo $row['id'] ?>"><?php echo $row['minus_votes'] ?></span>
                                <blockquote>
                                    <?php echo $row['answer'] ?>
                                    <?php if($row['file_upload'] != ""){ ?>
                                        <a style="font-size: 18px;" href="answer_uploads/<?php echo $row['file_upload'] ?>" target="_blank"><?php echo $row['file_upload'] ?></a>
                                    <?php }?>
                                </blockquote>
                                
                            </div>
                            <ul class="comments-meta">
                                <li style="color: blue;"><?php echo $row['username'] ?>
                                </li>
                                <li class="reply" style="color: blue;"><?php echo date("jS M Y H:i:s", strtotime($row['answered_date'])) ?></li>
                                </li>
                            </ul>
                            <!--/.media-body -->
                        </li>  
                        
                        <?php 
                                        }
                                        mysqli_free_result($result);
                                    }
                                }
                            }
                        ?>
                    </ol>
                    <!-- .comment-list -->
                </div>
            </div>
        </div>
        <div class="post-comments">
            <div class="container">
                <div class="col-xl-8 mx-lg-auto">
                    <form action="save_answer.php" method="POST" id="answer_form" enctype="multipart/form-data">
                        <div class="row" style="text-align: center;">
                            <div class="col-lg-12">
                                <label style="font-size: 18px;font-weight: 600;">Your Answer</label>
                            </div>
                        </div><br>
                        <div class="row">                            
                            <div class="col-lg-12">
                                <div class="form-group">                                    
                                    <textarea style="text-align: left !important;" type="text" class="form-control" rows="10" id="desc" name="desc"></textarea>
                                    <input type="hidden" id="question_id" name="question_id" value="<?php echo $id; ?>">
                                </div>
                            </div>                            
                        </div><br>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">  
                                    <label style="font-size: 18px;font-weight: 500;">Upload answer</label> <br>                                
                                    <input type="file" id="file_upload" name="file_upload" accept=".pdf,.doc">
                                </div>
                            </div>
                        </div>
                        <div class="row" style="text-align: center;">
                            <div class="col-lg-12">
                                <input type="button" onclick="submitForm()" class="btn btn-primary btn-lg" value="Post Your Answer">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>        
    </div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js'></script>

    <script>
        var appEditor;

        ClassicEditor
        .create( document.querySelector( '#desc' ) )
        .then( editor => {
            appEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );

        function submitForm() {
            var text = appEditor.getData();            

            if(text.indexOf('&nbsp;') >= 0) {
                alert(text);
                $.notify('Please, remove &nbsp; tags or enter Answer !!', {
                    position:'top right',
                    className: 'error',
                    autoHideDelay: 5000
                    });  
            } else {
                $("#answer_form").submit();
            }
        }

        function upVote(id) {
            $.ajax({
                url: 'vote.php',
                type: 'POST',
                data: {
                    answer_id: id,
                    vote: 1
                },
                success: function(data) {
                    var item=JSON.parse(data);

                    if(item.message_type == "L") {
                        $.notify('Please, login to vote for this answer !!', {
                            position:'top right',
                            className: 'error',
                            autoHideDelay: 7000
                        });
                    } else if(item.message_type == "E") {
                        $.notify('Voting failed !!', {
                            position:'top right',
                            className: 'error',
                            autoHideDelay: 7000
                        });
                    } else {
                        $.notify('Vote successfully added..', {
                            position:'top right',
                            className: 'success',
                            autoHideDelay: 3000
                        });
                        document.getElementById('plus_vote_id'+id).innerHTML = item.plus_votes;
                        document.getElementById('minus_vote_id'+id).innerHTML = item.minus_votes;
                    }
                }
            });
        }

        function downVote(id) {
            $.ajax({
                url: 'vote.php',
                type: 'POST',
                data: {
                    answer_id: id,
                    vote: -1
                },
                success: function(data) {
                    var item=JSON.parse(data);

                    if(item.message_type == "L") {
                        $.notify('Please, login to vote for this answer !!', {
                            position:'top right',
                            className: 'error',
                            autoHideDelay: 7000
                        });
                    } else if(item.message_type == "E") {
                        $.notify('Voting failed !!', {
                            position:'top right',
                            className: 'error',
                            autoHideDelay: 7000
                        });
                    } else {
                        $.notify('Vote successfully added..', {
                            position:'top right',
                            className: 'success',
                            autoHideDelay: 3000
                        });
                        document.getElementById('plus_vote_id'+id).innerHTML = item.plus_votes;
                        document.getElementById('minus_vote_id'+id).innerHTML = item.minus_votes;
                    }
                }
            });
        }
    </script>

</body>
</html>