<?php
session_start();
if (isset($_SESSION["index"]) && isset($_SESSION['is_admin']) && isset($_SESSION['has_locker'])) {
    if ($_SESSION['is_admin']) {
        header("Location:admin/dashboard.php");
    } else if (!$_SESSION['has_locker']) {
        header("Location:choose_locker.php");
    }
} else {
    header("Location:login.php");
}

include ('includes/db/db.php');
include ('includes/templates/header.php');
include ('includes/templates/navbar.php');

$index = $_SESSION["index"];
$user_id = $usersData[$index]["user_id"];
$user_email = $usersData[$index]["email"];
$user_name = $usersData[$index]["full_name"];
$my_locker_id = $usersData[$index]["locker_id"];
$my_locker_index;

for ($i = 0; $i < count($lockersData); $i++) {
    if ($lockersData[$i]["locker_id"] == $my_locker_id) {
        $my_locker_index = $i;
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("location:login.php");
} else if (isset($_POST['unreserve'])) {
    global $conn, $user_id;
    updateData($conn, 'users', ['locker_id' => null], 'user_id', $user_id);
    updateData($conn, 'lockers', ['status' => 'avilable'], 'locker_id', $my_locker_id);
    $_SESSION['has_locker'] = false;
    header("Location: choose_locker.php");
}

?>
<form class="list-support" method="post">
    <button type="submit" name="logout">
        <i class="fa-solid fa-arrow-up-from-bracket fa-rotate-270"></i>
    </button>
    <button type="submit" name="unreserve">
        <i class="fa-regular fa-calendar-xmark"></i>
    </button>
</form>
<section class="home locker_details min-vh-100">
    <div class="container">
        <div class="bg-dark bg-opacity-50 rounded-5 text-white py-5 px-3">
            <h1 class="text-center fw-bold">خزانتى</h1>
            <ul>
                <li>اسم الطالب : <?php echo $user_name ?></li>
                <li>ايميل الطالب : <?php echo $user_email ?></li>
                <li>رقم الخزانة : <?php echo $lockersData[$my_locker_index]["locker_id"] ?></li>
                <li>مكان الخزانة : <?php echo $lockersData[$my_locker_index]["floor"] ?></li>
                <li>محنوى الخزانة : <?php echo $lockersData[$my_locker_index]["content"] ?></li>
            </ul>
        </div>
    </div>
</section>

<?php
include ('includes/templates/footer.php');
?>