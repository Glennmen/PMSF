<?php
include('config/config.php');
?>
<!DOCTYPE html>
<html lang="<?= $locale ?>">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="PokeMap">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#3b3b3b">
    <!-- Fav- & Apple-Touch-Icons -->
    <!-- Favicon -->
    <link rel="shortcut icon" href="static/appicons/favicon.ico"
          type="image/x-icon">
    <!-- non-retina iPhone pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/114x114.png"
          sizes="57x57">
    <!-- non-retina iPad pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/144x144.png"
          sizes="72x72">
    <!-- non-retina iPad iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/152x152.png"
          sizes="76x76">
    <!-- retina iPhone pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/114x114.png"
          sizes="114x114">
    <!-- retina iPhone iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/120x120.png"
          sizes="120x120">
    <!-- retina iPad pre iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/144x144.png"
          sizes="144x144">
    <!-- retina iPad iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/152x152.png"
          sizes="152x152">
    <!-- retina iPhone 6 iOS 7 -->
    <link rel="apple-touch-icon" href="static/appicons/180x180.png"
          sizes="180x180">
    <script>
        var token = '<?php echo (!empty($_SESSION['token'])) ? $_SESSION['token'] : ""; ?>';
    </script>
    <link rel="stylesheet" href="static/dist/css/app.min.css">
    <link rel="stylesheet" href="static/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.css">
    <script src="static/js/vendor/modernizr.custom.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body id="top">
<div class="wrapper">
<?php
if ($enableLogin === true) {
    if (isset($_POST['submit_updatePwd'])) {
        if (!empty($_POST["password"]) && ($_POST["password"] == $_POST["repassword"])) {
            $passwordErr = '';
            $password = $_POST["password"];
            
            if (strlen($_POST["password"]) <= '5') {
                $passwordErr = i8ln('Your password must contain at least 6 characters!');
            } elseif (!preg_match("#[0-9]#", $password)) {
                $passwordErr = i8ln('Your password must contain at least 1 number!');
            } elseif (!preg_match("#[A-Z]#", $password)) {
                $passwordErr = i8ln('Your password must contain at least 1 capital letter!');
            } elseif (!preg_match("#[a-z]#", $password)) {
                $passwordErr = i8ln('Your password must contain at least 1 lowercase letter!');
            }
        } else {
            $passwordErr = i8ln('Your passwords didn\'t match!');
        }
        
        if (empty($passwordErr)) {
            $db->update("users", [
                "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
                "temp_password" => null
            ], [
                "email" => $_SESSION['user']->email
            ]);

            unset($_SESSION['user']->updatePwd);
            
            header("Location: .");
            die();
        }
    }

    if (isset($_POST['submit_login'])) {
        $info = $db->query(
            "SELECT email, password, expire_timestamp, temp_password FROM users WHERE email = :email", [
                ":email" => $_POST['email']
            ]
        )->fetch();

        if (password_verify($_POST['password'], $info['password']) == 1 || password_verify($_POST['password'], $info['temp_password']) == 1) {
            
            $_SESSION['user']->email = $info['email'];
            $_SESSION['user']->expire_timestamp = $info['expire_timestamp'];

            if (password_verify($_POST['password'], $info['password']) == 1) {
            
                if (!empty($info['temp_password'])) {
                    $db->update("users", [
                        "temp_password" => null
                    ], [
                        "email" => $_POST['email']
                    ]);
                }

                header("Location: .");
                die();

            } else {
                
                $_SESSION['user']->updatePwd = 1;
                
                header("Location: /login.php");
                die();
                
            }
        }
    }
    
    if (isset($_POST['submit_forgotPwd'])) {

        $count = $db->count("users",[
            "email" => $_POST['email']
        ]);
        
        if ($count == 1) {
            
            echo "Email found!";
            $randomPwd = generateRandomString();
            
            $db->update("users", [
                "temp_password" => password_hash($randomPwd, PASSWORD_DEFAULT)
            ], [
                "email" => $_POST['email']
            ]);
            
            echo "Your new password is set to {$randomPwd}";
            
            $message = "";
            $message .= "Dear {$_POST['email']},<br><br>";
            $message .= "Your password has been reset.<br>";
            $message .= "If you haven't requested a new password you can ignore this email.<br>";
            $message .= "Your old password is still working.<br><br>";
            $message .= "New password: {$randomPwd}<br><br>";
            
            if ($discordUrl) {
                $message .= "For support, ask your questions in the <a href='{$discordUrl}'>discord guild</a>!<br><br>";
            }
            $message .= "Best Regards,<br>Admin";
            if ($title) {
                $message .= " @ {$title}";
            }
            
            $subject = "[{$title}] - Password Reset";
            $headers = "From: no-reply@{$_SERVER['SERVER_NAME']}" . "\r\n" .
                "Reply-To: no-reply@{$_SERVER['SERVER_NAME']}" . "\r\n" .
                'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($_POST['email'], $subject, $message, $headers);
        }
        
        header("Location: ?sentPwd");
        die();
    }
    
    if (isset($_GET['resetPwd'])) {
    ?>
        <p><h2>[<?php echo "<a href='.'>{$title}</a>] - "; echo i8ln('Forgot password'); ?></h2></p>
        <form action='' method='POST'>
            <table>
                <tr>
                    <th><?php echo i8ln('E-mail'); ?></th><td><input type="text" name="email" required></td>
                </tr>
                <tr>
                    <td id="one-third"><input id="margin" type="submit" name="submit_forgotPwd"><a class='button' href='/login.php'>Back</a></td><td></td>
                </tr>
            </table>
        </form>
    <?php
    } elseif (!empty($_SESSION['user']->updatePwd)) {
        ?>
        <p><h2>[<?php echo "<a href='.'>{$title}</a>] - "; echo i8ln('Change your password.'); ?></h2></p>
        <form action='' method='POST'>
            <table>
                <tr>
                    <th><?php echo i8ln('New password'); ?></th><td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <th><?php echo i8ln('Confirm password'); ?></th><td><input type="password" name="repassword" required></td>
                </tr>
                <?php
                if (!empty($passwordErr)) {
                ?>
                <tr>
                    <th><?php echo i8ln('Message'); ?></th>
                    <td><input type="text" name="errMess" value="<?php echo $passwordErr; ?>" id="redBox" disabled></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td id="one-third"><input id="margin" type="submit" name="submit_updatePwd"><a class='button' href='/login.php'>Back</a></td><td></td>
                </tr>
            </table>
        </form>
   <?php
    } else {
    ?>
        <p><h2>[<?php echo "<a href='.'>{$title}</a>] - "; echo i8ln('Login'); ?></h2></p>
        <form action='' method='POST'>
            <table>
                <tr>
                    <th><?php echo i8ln('E-mail'); ?></th><td><input type="text" name="email" required <?php if(isset($_POST['submit_login'])) { echo "value='$_POST[email]'"; } ?> placeholder="E-mail"></td>
                </tr>
                <tr>
                    <th><?php echo i8ln('Password'); ?></th><td><input type="password" name="password" required placeholder="Password"></td>
                </tr>
                <?php
                if (isset($_POST['submit_login']) && password_verify($_POST['password'], $info['password']) != 1) {
                ?>
                <tr>
                    <th><?php echo i8ln('Message'); ?></th>
                    <td><input type="text" name="errMess" value="<?php echo i8ln('Wrong credentials'); ?>" id="redBox" disabled></td>
                </tr>
                <?php
                } elseif (isset($_GET['sentPwd'])) {
                ?>
                    <tr>
                        <th><?php echo i8ln('Message'); ?></th>
                        <td><input type="text" name="errMess" value="<?php echo i8ln('An email has been sent to the specified email.'); ?>" id="greenBox" disabled></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td id="one-third"><input id="margin" type="submit" name="submit_login"><a class='button' href='?resetPwd'>Reset Password</a></td><td></td>
                </tr>
            </table>
        </form>
   <?php
    }

} else {
    header("Location: .");
}
    ?>
</div>
</body>
</html>