<?php
session_start();

if (isset($_SESSION['index'])) {
    if ($_SESSION['is_admin']) {
        header('Location:admin/dashboard.php');
    } elseif ($_SESSION['has_locker']) {
        header('Location:locker_details.php');
    } else {
        header('Location:choose_locker.php');
    }
    exit;
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'includes/db/db.php';
include 'includes/templates/header.php';
require 'includes/templates/navbar.php';

$error = false;
$userIndex = -1;

if (isset($_POST['submit'])) {
    $user_input = $_POST['user'];
    $password_input = $_POST['password'];

    foreach ($usersData as $index => $user) {
        if (($user_input === $user['full_name'] || $user_input === $user['email']) && $password_input === $user['password']){
            $_SESSION['index'] = $index;
            $_SESSION['is_admin'] = $user['role'] === 'admin';
            $_SESSION['has_locker'] = $user['locker_id'] !== null;

            if ($_SESSION['is_admin']) {
                $_SESSION['login-admin'] = $user; 
                header('Location:admin/dashboard.php');
            } elseif ($_SESSION['has_locker']) {
                header('Location:locker_details.php');
            } else {
                header('Location:choose_locker.php');
            }
            exit;
        }
    }
    $error = true;
}
?>

<section class="home login min-vh-100">
    <div class="container">
        <div class="row align-items-center justify-content-center flex-column">
            <div class="col-md-8 col-lg-6">
                <form method="POST" class="bg-white px-5 py-3 bg-opacity-50 rounded-4">
                    <div class="mb-3">
                        <label for="user" class="form-label text-light">عنوان البريد الاكترونى :</label>
                        <input type="text" name="user"
                            class="form-control border-3 <?php echo $error ? 'border-danger' : '' ?>" id="user"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-light">كلمه المرور :</label>
                        <input type="password" name="password"
                            class="form-control border-3 <?php echo $error ? 'border-danger' : '' ?>" id="password"
                            required />
                    </div>
                    <small
                        class="fw-bold text-danger mb-1 d-block text-center <?php echo $error ? 'visible' : 'invisible' ?>">
                        المستخدم غير صحيح حاول مره اخري
                    </small>
                    <button name="submit" type="submit" class="btn btn-primary w-100 py-2">تسجيل الدخول</button>
                    <div class="text-center fw-bold mt-2">
                        <a href="./index.php" class="btn btn-link text-primary-emphasis">الصفحة الرئيسية</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/templates/footer.php'; ?>