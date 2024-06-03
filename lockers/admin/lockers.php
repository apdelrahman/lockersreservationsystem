<?php
session_start();

// Check if the user is logged in and has necessary session variables set
if (isset($_SESSION["index"]) && isset($_SESSION['is_admin']) && isset($_SESSION['has_locker'])) {
    if (!$_SESSION['is_admin']) {
        if ($_SESSION['has_locker']) {
            header("Location:../locker_details.php");
            exit();
        } else {
            header("Location:../choose_locker.php");
            exit();
        }
    }
} else {
    header("Location:../login.php");
    exit();
}

include('init.php');

$page = "All";

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if ($page == "All") {
    $statement = $connect->prepare("SELECT * FROM lockers");
    $statement->execute();
    $lockerCount = $statement->rowCount();
    $result = $statement->fetchAll();
    ?>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-10 m-auto">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                    unset($_SESSION['message']);
                    header("Refresh:3;url=lockers.php"); // Refresh after 3 seconds
                }
                ?>
                <h3 class="text-center">Lockers Table
                    <span class="badge badge-primary"><?php echo $lockerCount; ?></span>
                    <a href="?page=create" class="btn btn-success">Add New Locker</a>
                </h3>
                <table class="table table-dark text-center">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Floor</td>
                            <td>Status</td>
                            <td>Content</td>
                            <td>College ID</td>
                            <td>Operation</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $item) {
                        ?>
                            <tr>
                                <td><?php echo $item['locker_id']; ?></td>
                                <td><?php echo $item['floor']; ?></td>
                                <td><?php echo $item['status']; ?></td>
                                <td><?php echo $item['content']; ?></td>
                                <td><?php echo $item['college_id']; ?></td>
                                <td>
                                    <a href="lockers.php?page=show&locker_id=<?php echo $item['locker_id']; ?>" class="btn btn-success"><i class="fa-solid fa-eye fa-xs"></i></a>
                                    <a href="?page=edit&locker_id=<?php echo $item['locker_id']; ?>" class="btn btn-primary"><i class="fa-solid fa-pen-to-square fa-xs"></i></a>
                                    <a href="?page=delete&locker_id=<?php echo $item['locker_id']; ?>" class="btn btn-danger"><i class="fa-solid fa-trash fa-xs"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end container -->

<?php
} else if ($page == "show") {
    if (isset($_GET['locker_id'])) {
        $locker_id = $_GET['locker_id'];
    }

    $statement = $connect->prepare("SELECT * FROM lockers WHERE locker_id = ?");
    $statement->execute(array($locker_id));
    $item = $statement->fetch();
    ?>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-10 m-auto">
                <h3 class="text-center">Details Lockers Table <span class="badge badge-primary">1</span></h3>
                <table class="table table-dark text-center">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Floor</td>
                            <td>Status</td>
                            <td>Content</td>
                            <td>College ID</td>
                            <td>Operation</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $item['locker_id']; ?></td>
                            <td><?php echo $item['floor']; ?></td>
                            <td><?php echo $item['status']; ?></td>
                            <td><?php echo $item['content']; ?></td>
                            <td><?php echo $item['college_id']; ?></td>
                            <td>
                                <a href="lockers.php?page=All" class="btn btn-success"><i class="fa-solid fa-house fa-xs"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- end container -->

<?php
} else if ($page == "delete") {
    if (isset($_GET['locker_id'])) {
        $locker_id = $_GET['locker_id'];

        // Delete the locker
        $deleteLockerStatement = $connect->prepare("DELETE FROM lockers WHERE locker_id = ?");
        $deleteLockerStatement->execute(array($locker_id));

        // Remove locker ID from lockers table
        $updatelockersStatement = $connect->prepare("UPDATE lockers SET locker_id = NULL WHERE locker_id = ?");
        $updatelockersStatement->execute(array($locker_id));

        $_SESSION['message'] = "Successfully deleted locker and removed associated user references";
        header("location:lockers.php");
        exit();
    }
} else if ($page == "create") {
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-md-10 m-auto">
            <h3 class="text-center">Insert Locker Data</h3>
            <form method="POST" action="?page=savenew">
                <label>Floor</label>
                <select name="floor" class="form-control mb-3">
                    <option value="الأول">الأول</option>
                    <option value="الثاني">الثاني</option>
                    <option value="الثالث">الثالث</option>
                    <option value="الرابع">الرابع</option>
                    <option value="الخامس">الخامس</option>
                    <option value="السادس">السادس</option>
                </select>

                <label>Content</label>
                <input type="text" name="content" placeholder="Input the content" class="form-control mb-3">

                <label>College</label>
                <select name="college" class="form-control mb-3">
                    <option value="1">أمن المعلومات</option>
                    <option value="2">نظم المعلومات</option>
                    <option value="3">هندسة الحاسب</option>
                </select>

                <input type="submit" class="btn btn-success btn-block" value="Create New Locker">
            </form> <!-- end of form -->
        </div> <!-- end of col -->
    </div> <!-- end of row -->
</div> <!-- end of container -->

<?php
} else if ($page == "savenew") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $floor = $_POST['floor'];
        $content = $_POST['content'];
        $college = $_POST['college'];

        $statement = $connect->prepare("INSERT INTO lockers (`floor`, content, `college_id`) VALUES (?, ?, ?)");

        $statement->execute(array($floor, $content, $college));
        $_SESSION['message'] = "Locker has been created successfully";
        header("location:lockers.php?page=All");
        exit();
    }
} else if ($page == "edit") {
    if (isset($_GET['locker_id'])) {
        $locker_id = $_GET['locker_id'];
        $statement = $connect->prepare("SELECT * FROM lockers WHERE locker_id = ?");
        $statement->execute(array($locker_id));
        $result = $statement->fetch();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h3 class="text-center mt-5">Edit Locker Data</h3>
                    <form action="?page=saveedit" method="POST">
                        <label>ID</label>
                        <input type="text" name="new_id" class="form-control mb-3" value="<?php echo $result['locker_id']; ?>">

                        <label>FLOOR</label>
                        <select name="floor" class="form-control mb-3">
                            <option value="الأول" <?php if ($result['floor'] == 'الأول') echo 'selected'; ?>>الأول</option>
                            <option value="الثاني" <?php if ($result['floor'] == 'الثاني') echo 'selected'; ?>>الثاني</option>
                            <option value="الثالث" <?php if ($result['floor'] == 'الثالث') echo 'selected'; ?>>الثالث</option>
                            <option value="الرابع" <?php if ($result['floor'] == 'الرابع') echo 'selected'; ?>>الرابع</option>
                            <option value="الخامس" <?php if ($result['floor'] == 'الخامس') echo 'selected'; ?>>الخامس</option>
                            <option value="السادس" <?php if ($result['floor'] == 'السادس') echo 'selected'; ?>>السادس</option>
                        </select>

                        <label>CONTENT</label>
                        <input type="text" name="content" class="form-control mb-3" value="<?php echo $result['content']; ?>">

                        <label>College</label>
                        <select name="college" class="form-control mb-3">
                            <option value="1" <?php if ($result['college_id'] == '1') echo 'selected'; ?>>أمن المعلومات</option>
                            <option value="2" <?php if ($result['college_id'] == '2') echo 'selected'; ?>>نظم المعلومات</option>
                            <option value="3" <?php if ($result['college_id'] == '3') echo 'selected'; ?>>هندسة الحاسب</option>
                        </select>
                        <input type="hidden" name="locker_id" value="<?php echo $locker_id; ?>">
                        <input type="submit" class="btn btn-success btn-block" value="Save Changes">
                        <a href="lockers.php" class="btn btn-primary btn-block mt-2">Cancel</a>

                    </form>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container -->
        <?php
    }
} else if ($page == "saveedit") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $new_id = $_POST['new_id'];
        $floor = $_POST['floor'];
        $content = $_POST['content'];
        $college = $_POST['college'];
        try {
            $statement = $connect->prepare('UPDATE lockers SET locker_id = ?, floor = ?, content = ?, college_id = ? WHERE locker_id = ?');
            $statement->execute(array($new_id, $floor, $content, $college, $locker_id));
            $_SESSION['message'] = "Updated successfully";
            header("Location: lockers.php");
            exit();
        } catch (PDOException $e) {
            echo "<h3 class='alert alert-danger text-center'>Error updating locker</h3>";
            $_SESSION['error_id'] = $new_id;
            $_SESSION['error_floor'] = $floor;
            $_SESSION['error_content'] = $content;
            $_SESSION['error_college'] = $college;
            header("Refresh:3;url=lockers.php?page=edit&locker_id=" . $locker_id);
            exit();
        }
    }
}

include('includes/templates/footer.php');
?>
