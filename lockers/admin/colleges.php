<?php
session_start();
include('init.php');

// Fetch all colleges
$statement = $connect->prepare("SELECT * FROM colleges");
$statement->execute();
$collegeCount = $statement->rowCount();
$result = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleges</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-10 m-auto">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                    unset($_SESSION['message']);
                    header("Refresh:3; url=colleges.php"); // Refresh after 3 seconds
                }
                ?>
                <h3 class="text-center">Colleges Table
                    <span class="badge badge-primary"><?php echo $collegeCount; ?></span>
                </h3>
                <table class="table table-dark text-center">
                    <thead>
                        <tr>
                            <th>College ID</th>
                            <th>College Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $item) {
                        ?>
                            <tr>
                                <td><?php echo $item['college_id']; ?></td>
                                <td><?php echo $item['college_name']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end container -->

    <?php include('includes/templates/footer.php'); ?>
</body>
</html>
