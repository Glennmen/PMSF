<?php
include('config/config.php');
if ($enableLogin === true) {
    if (isset($_POST['submit_updatePwd'])) {
        if (!empty($_POST["password"]) && ($_POST["password"] == $_POST["repassword"])) {
            $passwordErr = '';
            $password = $_POST["password"];
            
            if (strlen($_POST["password"]) <= '5') {
                $passwordErr = "<b>" . i8ln('Your password must contain at least 6 characters!') . "</b><br>";
            } elseif (!preg_match("#[0-9]#", $password)) {
                $passwordErr = "<b>" . i8ln('Your password must contain at least 1 number!') . "</b><br>";
            } elseif (!preg_match("#[A-Z]#", $password)) {
                $passwordErr = "<b>" . i8ln('Your password must contain at least 1 capital letter!') . "</b><br>";
            } elseif (!preg_match("#[a-z]#", $password)) {
                $passwordErr = "<b>" . i8ln('Your password must contain at least 1 lowercase letter!') . "</b><br>";
            }
        } else {
            $passwordErr = "<b>" . i8ln('Your passwords didn\'t match!') . "</b><br>";
        }
        
        if (empty($passwordErr)) {
            $db->update("users", [
                "password" => sha1($_POST['password'] . $salt),
                "updatePwd" => 0
            ], [
                "email" => $_SESSION['user']->email
            ]);
            
            header("Location: .");
            die();
        } else {
            echo $passwordErr;
        }
    }

    if (isset($_POST['submit_login'])) {
        $info = $db->query(
            "SELECT email, expire_timestamp, updatePwd FROM users WHERE email = :email AND password = :password", [
                ":email" => $_POST['email'],
                ":password" => sha1($_POST['password'] . $salt),
            ]
        )->fetch();

        if ($info['email']) {
            $_SESSION['user']->email = $info['email'];
            $_SESSION['user']->expire_timestamp = $info['expire_timestamp'];
            $_SESSION['user']->updatePwd = $info['updatePwd'];
            if ($info['updatePwd'] == 1) {
                header("Location: /login.php");
                die();
            } else {
                header("Location: .");
                die();
            }
        } else {
            echo i8ln('Incorrect username or password');
        }
    }

    if ($_SESSION['user']->updatePwd == 1) {
        ?>
		<?php echo i8ln('Please change your password.'); ?>
		<form action='' method='POST'>
			<table>
				<tr>
					<th><?php echo i8ln('New password'); ?></th><td><input type="password" name="password" required></td>
				</tr>
				<tr>
					<th><?php echo i8ln('Confirm password'); ?></th><td><input type="password" name="repassword" required></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit_updatePwd"></td><td></td>
				</tr>
			</table>
		</form>
   <?php
    } else {
        ?>
		<form action='' method='POST'>
			<table>
				<tr>
					<th><?php echo i8ln('E-mail'); ?></th><td><input type="text" name="email" required></td>
				</tr>
				<tr>
					<th><?php echo i8ln('Password'); ?></th><td><input type="password" name="password" required></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit_login"></td><td></td>
				</tr>
			</table>
		</form>
   <?php
    }
} else {
    header("Location: .");
}
    ?>