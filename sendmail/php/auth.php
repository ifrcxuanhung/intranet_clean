<?php
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['_newsletter_loggedin']);
    $newsletter->logout();
    header("Location: index.php");
    exit;
}

$login_status = (isset($_SESSION['_newsletter_loggedin']) && $_SESSION['_newsletter_loggedin']);

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $login_status = $newsletter->login($db, $_REQUEST['username'], $_REQUEST['password']);
}

if ($login_status) {
    /* support for multiple logins at one time. */
    $_SESSION['_newsletter_loggedin'] = $login_status;
}


if (!$login_status) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <title><?php echo _l('Newsletter System'); ?>:: Login</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="author" content="">

            <!-- Le styles -->
            <link href="layout/css/bootstrap.css" rel="stylesheet">
            <link href="layout/css/main.css" rel="stylesheet">

            <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
            <!--[if lt IE 9]>
              <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->
        </head>

        <body>
            <div class="login-container">
                <div class="login">
                    <div class="avatar">
                        <img src="images/LOGO_IFRC_NEW_BLUE.jpg" alt="">
                    </div>
                    <form id="formLogin" action="" method="post">
                        <?php
                        if (isset($_REQUEST['username']) || isset($_REQUEST['password'])) {
                            echo '<div class="error-login" style="display: block;">Username or password incorrect!</div>';
                        }
                        ?>
                        <input type="text" name="username" class="login-input" placeHolder="Enter your username here..." value="<?php echo (_DEMO_MODE) ? $newsletter->settings['username'] : $_REQUEST['username']; ?>" />
                        <input type="password" class="password-input" name="password" value="<?php echo (_DEMO_MODE) ? $newsletter->settings['password'] : ''; ?>" placeHolder="Enter your password here..." />
                        <!-- div class="remember fLeft">
                            <label class="form-button">
                                <input type="checkbox" /> <span>Remember me</span>
                            </label>
                        </div-->
                        <a href="javascript:void()" onclick="document.getElementById('formLogin').submit();" class="loginbutton fRight button small-button button-red">Login</a>
                        <div class="clearfix"></div>
                    </form>
                </div>

                <!-- div class="login-footer">
                    Not a registered user yet? <a href="#">Sign up now!</a>
                </div-->
                <span>2013 © insidetree admin template</span>
            </div>
        </body>

        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="layout/js/jquery-1.8.3.js"></script>
        <script src="layout/js/ui/jquery-ui-1.9.2.custom.js"></script>
        <script src="layout/js/uniform/jquery.uniform.js"></script>
        <script src="layout/js/jquery-splitter/splitter.js"></script>
        <script src="layout/js/cookie/jquery.cookie.js"></script>
        <script src="layout/js/masonry/jquery.masonry.js"></script>
        <script src="layout/js/masked/jquery.maskedinput.js"></script>
        <script src="layout/js/powertip/jquery.powertip.js"></script>
        <script src="layout/js/range-picker/daterangepicker.js"></script>
        <script src="layout/js/range-picker/date.js"></script>
        <script src="layout/js/fancybox/jquery.fancybox.js"></script>
        <script src="layout/js/flexslider/jquery.flexslider.js"></script>
        <script src="layout/js/form-validate/jquery.validate.js"></script>
        <script src="layout/js/scrollbar/jquery.mCustomScrollbar.js"></script>
        <script src="layout/js/ibutton/jquery.ibutton.js"></script>
        <script src="layout/js/password-meter/password_strength.js"></script>
        <script src="layout/js/bootstrap-wizards/jquery.bootstrap.wizard.js"></script>
        <script src="layout/js/rating/jquery.rating.js"></script>
        <script src="layout/js/bootstrap.js"></script>
        <script src="layout/js/chosen/chosen.jquery.js"></script>
        <script src="layout/js/forms.js"></script>
        <script src="layout/js/main.js"></script>
        <script type="text/javascript">
            $(document).keypress(function(e) {
                if(e.which == 13) {
                    $(".loginbutton").click();
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}