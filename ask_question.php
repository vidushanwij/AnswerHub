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
                    <li><a href="vacancies.php">Careers</a></li>
                    <li><a href="ask_question.php" class="active">Ask a Question</a></li>
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
    <section class="search-section home-search ask_question_bg">
        <div class="masthead text-center" id="vacs">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-md-auto">
                        <h1 style="margin-bottom: 20%;color: #fff;">Ask a Question</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>  
    
    <section class="topics">
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <h5 class="card-header">Ask Question</h5>
                        <div class="card-body">
                            <form action="save_question.php" method="POST" id="questionForm">
                                <div class="form-group">
                                    <label style="font-size: 18px;font-weight: 600;">Question Title</label><br>
                                    <label style="font-size: 15px;color: grey;">Be specific and imagine you’re asking a question to another person</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label style="font-size: 18px;font-weight: 600;">Details</label><br>
                                    <label style="font-size: 15px;color: grey;">Include all the information someone would need to answer your question</label>
                                    <textarea type="text" class="form-control" rows="10" id="details" name="details"></textarea>
                                </div>
                                <button type="button" onclick="submiForm()" name="save" class="btn btn-primary">Submit</button>
                            </form>                           
                        </div>
                    </div>
                </div>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js'></script>

    <script>
        var appEditor;

        ClassicEditor
        .create( document.querySelector( '#details' ) )
        .then( editor => {
            appEditor = editor;
        } )
        .catch( error => {
            console.error( error );
        } );

        function submiForm() {
            $title = $('#title').val();
            var text = appEditor.getData();            

            if($title == "") {
                $.notify('Please, enter title !!', {
                    position:'top right',
                    className: 'error',
                    autoHideDelay: 5000
                    });
            } else if(text.indexOf('&nbsp;') >= 0) {
                alert(text);
                $.notify('Please, remove &nbsp; tags or enter Question Details !!', {
                    position:'top right',
                    className: 'error',
                    autoHideDelay: 5000
                    });  
            } else {
                $("#questionForm").submit();
            }
        }
    </script>
</body>
</html>