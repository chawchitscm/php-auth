<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    include __DIR__.'/../db_connect.php';
    require_once __DIR__.'./../config.php';
    require '../vendor/autoload.php';

    session_start();

    $conf = new Config;
    $connection = openCon();

    if(isset($_POST['reset'])) {
        $email = $_POST['email'];

        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($connection, $query);
        
        $row= mysqli_fetch_array($result);
 
        if(is_array($row))
        {
            $token = md5($email).rand(10,9999);
            $expFormat = mktime(
                date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
            );
            $expDate = date("Y-m-d H:i:s",$expFormat);
            
            $query = "UPDATE user SET reset_link_token='$token' ,exp_date='$expDate' WHERE email='$email'";
            $update = mysqli_query($connection, $query);
 
            $link = "<a href='".(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/password-reset.php?key=$email&token=$token"."'>Click To Reset password</a>";
            
            $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->Host = $conf->mail_host;
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = $conf->mail_port;
            $phpmailer->Username = $conf->mail_username;
            $phpmailer->Password = $conf->mail_password;
            $phpmailer->From = $conf->mail_from;
            $phpmailer->FromName = $conf->from_name;
            $phpmailer->AddAddress($email, $row['username']);
            $phpmailer->Subject  =  'Reset Password';
            $phpmailer->IsHTML(true);
            $phpmailer->Body    = 'Click On This Link to Reset Password '.$link.'';
            if($phpmailer->Send())
            {
                $_SESSION['message'] = 'The reset password link has been sent. Check your mail.';
                header('location: ../forgot-password.php');
            }
            else
            {
                echo "Mail Error - >".$phpmailer->ErrorInfo;
            }
    }else{
        $_SESSION['error'] = 'Invalid email addresss.';
        header('location: ../forgot-password.php');
    }
}
