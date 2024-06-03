<?php
session_start();
include('init.php');

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

$page = "All";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if ($page == "All") {
    $statement = $connect->prepare("SELECT * FROM users");
    $statement->execute();
    $userCount = $statement->rowCount();
    $result = $statement->fetchAll();
    ?>
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-md-10 m-auto">
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<h4 class='text-center alert alert-success'>" . $_SESSION['message'] . "</h4>";
                    unset($_SESSION['message']);
                    header("Refresh:3;url=users.php"); // refresh after 3 seconds
                }
                ?>
                <h3 class="text-center">Users Table
                    <span class="badge badge-primary"><?php echo $userCount; ?></span>
                    <a href="?page=create" class="btn btn-success">Add New User</a>
                </h3>
                <table class="table table-dark text-center">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Email</td>
                            <td>Role</td>
                            <td>Created-at</td>
                            <td>Operation</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item['user_id']; ?></td>
                                <td><?php echo $item['full_name']; ?></td>
                                <td><?php echo $item['email']; ?></td>
                                <td><?php echo $item['role']; ?></td>
                                <td><?php echo $item['created_at']; ?></td>
                                <td>
                                    <a href="users.php?page=show&user_id=<?php echo $item['user_id']; ?>" class="btn btn-success"><i class="fa-solid fa-eye fa-xs"></i></a>
                                    <a href="?page=edit&user_id=<?php echo $item['user_id']; ?>" class="btn btn-primary"><i class="fa-solid fa-pen-to-square fa-xs"></i></a>
                                    <a href="?page=delete&user_id=<?php echo $item['user_id']; ?>&locker_id=<?php echo $item['locker_id']; ?>" class="btn btn-danger"><i class="fa-solid fa-trash fa-xs"></i></a>
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
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        $statement = $connect->prepare("SELECT * FROM users WHERE user_id = ?");
        $statement->execute(array($user_id));
        $item = $statement->fetch();
        ?>
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <h3 class="text-center">Details Users Table <span class="badge badge-primary">1</span></h3>
                    <table class="table table-dark text-center">
                        <thead>
                            <tr>
                                <td>ID</td>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Role</td>
                                <td>Created-at</td>
                                <td>Operation</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $item['user_id']; ?></td>
                                <td><?php echo $item['full_name']; ?></td>
                                <td><?php echo $item['email']; ?></td>
                                <td><?php echo $item['role']; ?></td>
                                <td><?php echo $item['created_at']; ?></td>
                                <td>
                                    <a href="users.php?page=All" class="btn btn-success"><i class="fa-solid fa-house fa-xs"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container -->
        <?php
    }
} else if ($page == "delete") {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $locker_id = $_GET['locker_id'];

        // Update locker status to 'available' for the locker reserved by this user
        $statement = $connect->prepare("UPDATE lockers SET status = 'available' WHERE locker_id = ?");
        $statement->execute(array($locker_id));

        // Delete the user
        $statement = $connect->prepare("DELETE FROM users WHERE user_id = ?");
        $statement->execute(array($user_id));

        $_SESSION['message'] = "Successfully deleted user and updated locker status";
        header("Location: users.php");
        exit();
    }
} else if ($page == "create") {
    ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-10 m-auto">
                <h3 class="text-center">Insert User Data</h3>
                <form method="POST" action="?page=savenew">
                    <label>ID</label>
                    <input type="text" name="id" placeholder="Insert your id" class="form-control mb-3" value="<?php 
                    if (isset($_SESSION['error_id'])) {
                        echo $_SESSION['error_id'];
                        unset($_SESSION['error_id']);
                    }
                    ?>">

                    <label>Name</label>
                    <input type="text" name="user" placeholder="Enter full name" class="form-control mb-3" value="<?php 
                    if (isset($_SESSION['error_name'])) {
                        echo $_SESSION['error_name'];
                        unset($_SESSION['error_name']);
                    }
                    ?>">

                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email" class="form-control mb-3" value="<?php 
                    if (isset($_SESSION['error_email'])) {
                        echo $_SESSION['error_email'];
                        unset($_SESSION['error_email']);
                    }
                    ?>">

                    <label>Password</label>
                    <input type="password" name="pass" placeholder="Enter password" class="form-control mb-3">

                    <label>Role</label>
                    <select name="role" class="form-control mb-3">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>

                    <input type="submit" class="btn btn-success btn-block" value="Create New User">
                </form> <!-- end of form -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
    <?php
} else if ($page == "savenew") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_POST['id'];
        $user = $_POST['user'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $role = $_POST['role'];
        try {    
            $statement = $connect->prepare('INSERT INTO users 
                (user_id, full_name, email, `password`, `role`, created_at)
                VALUES (?, ?, ?, ?, ?, now())');
            $statement->execute(array($id, $user, $email, $pass, $role));
            $_SESSION['message'] = "Created successfully";
            header("Location: users.php");
            exit();
        } catch (PDOException $e) {
            echo "<h3 class='alert alert-danger text-center'>Duplicated ID</h3>";
            $_SESSION['error_id'] = "Enter another id";
            $_SESSION['error_email'] = $email;
            $_SESSION['error_name'] = $user;
            header("Refresh:3;url=users.php?page=create");
            exit();
        }
    }
} else if ($page == "edit") {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $statement = $connect->prepare("SELECT * FROM users WHERE user_id = ?");
        $statement->execute(array($user_id));
        $result = $statement->fetch();
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10 m-auto">
                    <form action="?page=saveedit" method="POST">
                        <label>ID</label>
                        <input type="text" name="new_id" class="form-control mb-3" value="<?php echo $result['user_id']; ?>">
                        <label>Name</label>
                        <input type="text" name="user" class="form-control mb-3" value="<?php echo $result['full_name']; ?>">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control mb-3" value="<?php echo $result['email']; ?>">
                        <label>Password</label>
                        <input type="password" name="pass" class="form-control mb-3" value="<?php echo $result['password']; ?>">
                        <label>Role</label>
                        <select name="role" class="form-control mb-3">
                            <option value="admin" <?php if ($result['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                            <option value="user" <?php if ($result['role'] == 'user') echo 'selected'; ?>>User</option>
                        </select>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="submit" class="btn btn-success btn-block" value="Save Changes">
                        <a href="users.php" class="btn btn-primary btn-block mt-2">Cancel</a>
                    </form>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container -->
        <?php
    }
} else if ($page == "saveedit") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $new_id = $_POST['new_id'];
        $user = $_POST['user'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $role = $_POST['role'];
        $user_id = $_POST['user_id'];
        
        try {
            $statement = $connect->prepare('UPDATE users SET user_id = ?, full_name = ?, email = ?, `password` = ?, `role` = ? WHERE user_id = ?');
            $statement->execute(array($new_id, $user, $email, $pass, $role, $user_id));
            $_SESSION['message'] = "Updated successfully";
            header("Location: users.php");
            exit();
        } catch (PDOException $e) {
            echo "<h3 class='alert alert-danger text-center'>Error updating user</h3>";
            $_SESSION['error_id'] = $new_id;
            $_SESSION['error_email'] = $email;
            $_SESSION['error_name'] = $user;
            header("Refresh:3;url=users.php?page=edit&user_id=".$user_id);
            exit();
        }
    }
} else if ($page == "login") {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $statement = $connect->prepare("SELECT * FROM users WHERE email = ?");
        $statement->execute(array($email));
        $user = $statement->fetch();

        if ($user && $password == $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['is_admin'] = $user['role'] == 'admin';
            $_SESSION['has_locker'] = !empty($user['locker_id']);
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }
}

include('includes/templates/footer.php');
?>
