<?php
session_start();

// Check if the 'login-admin' session variable is set
if (isset($_SESSION['login-admin'])) {
    if (isset($_SESSION["index"]) && isset($_SESSION['is_admin']) && isset($_SESSION['has_locker'])) {
        if (!$_SESSION['is_admin']) {
            if ($_SESSION['has_locker']) {
                header("Location: ../locker_details.php");
                exit();
            } else {
                header("Location: ../choose_locker.php");
                exit();
            }
        }
    } else {
        header("Location: ../login.php");
        exit();
    }

    include('init.php');
    // Database queries
    $q1 = $connect->prepare("SELECT * FROM users");
    $q1->execute();
    $userCount = $q1->rowCount();

    $q2 = $connect->prepare("SELECT * FROM lockers");
    $q2->execute();
    $lockerCount = $q2->rowCount();

    $q3 = $connect->prepare("SELECT * FROM reservations");
    $q3->execute();
    $resCount = $q3->rowCount();

    $q4 = $connect->prepare("SELECT * FROM colleges");
    $q4->execute();
    $collCount = $q4->rowCount();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </head>
    <body>
        <div class="container mt-5 pt-5">
            <div class="row justify-content-center ">
                <div class="col-md-3 text-center">
                    <div class="box">
                        <i class="fa-solid fa-user fa-2xl"></i>
                        <h3 class="my-3">Users</h3>
                        <h5><?php echo $userCount ?></h5>
                        <a href="users.php" class="btn btn-success">Show</a>
                    </div> <!-- end box -->
                </div> <!-- end col-md-3 text-center -->

                <div class="col-md-3 text-center">
                    <div class="box">
                        <i class="fa-solid fa-boxes-stacked fa-2xl"></i>
                        <h3 class="my-3">Lockers</h3>
                        <h5><?php echo $lockerCount ?></h5>
                        <a href="lockers.php" class="btn btn-primary">Show</a>
                    </div><!-- end box -->
                </div> <!-- end col-md-3 text-center -->

                <div class="col-md-3 text-center">
                    <div class="box">
                        <i class="fa-solid fa-boxes-stacked fa-2xl"></i>
                        <h3 class="my-3">Reservations</h3>
                        <h5><?php echo $resCount ?></h5>
                        <a href="reservations.php" class="btn btn-danger">Show</a>
                    </div><!-- end box -->
                </div> <!-- end col-md-3 text-center -->

                <div class="col-md-3 text-center">
                    <div class="box">
                        <i class="fa-solid fa-boxes-stacked fa-2xl"></i>
                        <h3 class="my-3">Colleges</h3>
                        <h5><?php echo $collCount ?></h5>
                        <a href="colleges.php" class="btn btn-warning">Show</a>
                    </div><!-- end box -->
                </div> <!-- end col-md-3 text-center -->
            </div> <!-- end row -->
        </div> <!-- end container -->

        <?php include('includes/templates/footer.php'); ?>
    </body>
    </html>

    <?php
} else {
    // Display an error message if 'login-admin' session variable is not set
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5 pt-5">
            <div class="row justify-content-center ">
                <div class="col-md-6 text-center">
                    <div class="alert alert-danger" role="alert">
                        You need to log in first.
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Redirect to the login page after displaying the error message
        header("refresh:3; url=../login.php");
        exit();
        ?>
    </body>
    </html>

    <?php
}
?>
