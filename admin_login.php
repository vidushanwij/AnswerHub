<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Answer Hub</title>
    <link rel="shortcut icon" href="images/mini-logo.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme7.css">

    <style>
        .p-viewer2{
            float: right;
            margin-top: -45px;
            margin-right: 10px;
            position: relative;
            z-index: 1;
            cursor:pointer;
        }
    </style>

    <script src="js/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js'></script> 
</head>
<body>
    <?php
        if(isset($_SESSION['error_msg'])) {
            echo "
                <script type=\"text/javascript\">
                    $.notify('".$_SESSION['error_msg']."', {
                    position:'top right',
                    className: 'error',
                    autoHideDelay: 5000
                    });
                </script>
            ";
            unset($_SESSION['error_msg']);
        } 
    ?>

    <?php
        if(isset($_SESSION['success_msg'])) {
            echo "
                <script type=\"text/javascript\">
                    $.notify('".$_SESSION['success_msg']."', {
                    position:'top right',
                    className: 'success',
                    autoHideDelay: 5000
                    });
                </script>
            ";
            unset($_SESSION['success_msg']);
        } 
    ?>
    <div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="images/graphic3.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="website-logo-inside" style="text-align: center;">
                            <a href="index.php">
                                <div class="logo">
                                    <img class="logo-size" src="images/logo.png" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="page-links">
                            <a href="#" class="active">Admin Login</a>
                        </div>
                        <form action="check_admin_login.php" method="POST">
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" required>
                            <span class="p-viewer2">
                                <i class="fa fa-eye toggle-password" aria-hidden="true"></i>
                            </span>
                            <div class="form-button">
                                <button id="submit" type="submit" name="login" class="ibtn">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");

            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        });
    </script>
</body>
</html>