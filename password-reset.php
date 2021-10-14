<?php  
    include('db_connect.php'); 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-5">Reset Password Confirmation</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-success my-3">
                        <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                        ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="alert alert-danger my-3">
                        <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <?php
            if($_GET['key'] && $_GET['token']) {
                $connection = openCon();

                $email = $_GET['key'];
                $token = $_GET['token'];

                $query = "SELECT * FROM user WHERE reset_link_token='$token' and email='$email';";
                $result = mysqli_query($connection, $query);
                $row  = mysqli_fetch_array($result);

                $curDate = date("Y-m-d H:i:s");

                if ($row) {
                    if($row['exp_date'] >= $curDate){ 
        ?>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <form method="post" action="./services/update_password.php">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">
                            <input type="hidden" name="reset_link_token" value="<?php echo $token; ?>">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" name="con_password" required>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" name="update_password">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
        <?php 
                    } else {
        ?>
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger my-3">
                    This reset password link has already been expired.
                </div>
            </div>
        </div>
        <?php            
                    }
                } else {
        ?>
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger my-3">
                    This reset password link is no longer active.
                </div>
            </div>
        </div> 
        <?php
                }
            }
        ?>
    </div>
</body>
</html>