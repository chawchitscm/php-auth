<?php
    include __DIR__.'/../db_connect.php';
    session_start();

    $connection = openCon();

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM user WHERE email='$email'";
        $result = mysqli_query($connection, $query);
        $row  = mysqli_fetch_array($result);

        if (is_array($row) && password_verify($password, $row['password'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['username'] = $row['username'];
                header('location: ../index.php');  
        } else {
            $_SESSION['error'] = 'Invalid Uesrname or Password';
            header('location: ../login.php');
        }
    }
    