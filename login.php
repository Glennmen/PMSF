<?php
include('config/config.php');

if(isset($_POST['submit'])){
	
	$salt = "5up3r53cr3754l7";
	
	$info = $db->query(
		"SELECT email, expire_timestamp FROM users WHERE email = :email AND password = :password", [
			":email" => $_POST['email'],
			":password" => sha1($_POST['password'] . $salt),
		]
	)->fetch();

	if($info['email']) {
		$_SESSION['user']->email = $info['email'];
		$_SESSION['user']->expire_timestamp = $info['expire_timestamp'];
		
		header("Location: .");
		
	} else {
		echo "Incorrect username or password.";
	}
}

?>
<form action='' method='POST'>
	<table>
		<tr>
			<th>E-mail</th><td><input type="text" name="email" required></td>
		</tr>
		<tr>
			<th>Password</th><td><input type="password" name="password" required></td>
		</tr>
		<tr>
			<td><input type="submit" name="submit"></td><td></td>
		</tr>
	</table>
</form>