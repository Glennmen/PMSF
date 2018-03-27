<?php
include('config/config.php');
if ($enableLogin === true) {

    $json = json_decode(file_get_contents('php://input'), true);

    $id = !empty($json["id"]) ? $json["id"] : null;
    $product_id = !empty($json["product_id"]) ? $json["product_id"] : null;
    $email = !empty($json["email"]) ? $json["email"] : null;
    $value = !empty($json["value"]) ? $json["value"] : null;
    $quantity = !empty($json["quantity"]) ? $json["quantity"] : null;

    $timestamp = time();

    if (!empty($id) && !empty($product_id) && !empty($email) && !empty($value) && !empty($quantity)) {
        $db->insert("payments", [
            "selly_id" => $id,
            "product_id" => $product_id,
            "email" => $email,
            "value" => $value,
            "quantity" => $quantity,
            "timestamp" => $timestamp
        ]);

        $info = $db->query(
            "SELECT email, expire_timestamp FROM users WHERE email = :email", [
                ":email" => $email
            ]
        )->fetch();
        
        //31 days. (60 * 60 * 24 * 31) * months
        $addMonths = 2678400 * $quantity;
        
        $message = "Dear {$email},<br><br>";
        $message .= "Thank you for your purchase.<br>";

        if ($info['email']) {
            if ($info['expire_timestamp'] > time()) {
                $new_expire_timestamp = $info['expire_timestamp'] + $addMonths;
            } else {
                $new_expire_timestamp = time() + $addMonths;
            }
            $time = date("Y-m-d H:i", $new_expire_timestamp);
            
            $db->update("users", [
                "expire_timestamp" => $new_expire_timestamp
            ], [
                "email" => $info['email']
            ]);
            
            $message .= "Your new expire date is set to {$time}.<br><br>";
            $message .= "Login with {$info['email']} and your old password on the website.<br><br>";
        } else {
            $randomPwd = generateRandomString();
            $new_expire_timestamp = time() + $addMonths;
            $time = date("Y-m-d H:i", $new_expire_timestamp);
            
            $db->insert("users", [
                "email" => $email,
                "temp_password" => password_hash($randomPwd, PASSWORD_DEFAULT)
                "expire_timestamp" => $new_expire_timestamp
            ]);
            
            $message .= "Your expire date is set to {$time}.<br><br>";
            $message .= "<b>Credentials:</b><br>
    *********************************************************<br>
    <b>Email:</b> {$email}<br>
    <b>Password:</b> {$randomPwd}<br>
   *********************************************************<br><br>";
        }

        if ($discordUrl) {
            $message .= "For support, ask your questions in the <a href='{$discordUrl}'>discord guild</a>!<br><br>";
        }
        
        $message .= "Best Regards,<br>Admin";
        
        if ($title) {
            $message .= " @ {$title}";
        }
        
        $subject = "[{$title}] - Membership";
        $headers = "From: no-reply@{$_SERVER['SERVER_NAME']}" . "\r\n" .
            "Reply-To: no-reply@{$_SERVER['SERVER_NAME']}" . "\r\n" .
            'Content-Type: text/html; charset=ISO-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    } else {
        header("Location: .");
    }
} else {
    header("Location: .");
}