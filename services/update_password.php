<?php
    include __DIR__.'/../db_connect.php';
    session_start();

    $connection = openCon();

    if (isset($_POST['update_password'])) {
        $email  = $_POST['email'];
        $token    = $_POST['reset_link_token'];
        $password = $_POST['password'];
        $con_password = $_POST['con_password'];

        if ($password == $con_password) {
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "SELECT * FROM user WHERE reset_link_token='$token' AND email='$email'";
            $result    = mysqli_query($connection, $query);
            $row  = mysqli_fetch_array($result);
            if (is_array($row)) {
                $query = "UPDATE user SET password='$hash_password', reset_link_token=NULL ,exp_date=NULL WHERE email='$email'";
                mysqli_query($connection, $query);
                
                $_SESSION['message'] = 'Congratulations! Your password has been updated successfully.';
                header('location: ../login.php');
            } else {
                $_SESSION['error'] = 'Something wrong. Please try again.';
                header('location: ../password_reset.php');
            }
        } else {
            $_SESSION['error'] = 'Password not match.';
            $url = urldecode("?key=$email&token=$token");
            header("location: ../password_reset.php" . $url);
        }
    }