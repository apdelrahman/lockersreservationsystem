<?php
session_start();
if (isset($_SESSION["index"]) && isset($_SESSION['is_admin']) && isset($_SESSION['has_locker'])) {
  if ($_SESSION['is_admin']) {
    header("Location:admin/dashboard.php");
  } else if ($_SESSION['has_locker']) {
    header("Location:locker_details.php");
  }
} else {
  header("Location:login.php");
}

include ('includes/db/db.php');
include ('includes/templates/header.php');
include ('includes/templates/navbar.php');

echo $_SESSION['index'];
echo $_SESSION['is_admin'];
echo $_SESSION['is_admin'] ? 1 : 0;
echo $_SESSION['has_locker'] ? 1 : 0;
$index = $_SESSION["index"];
$user_id = $usersData[$index]["user_id"];
$user_name = $usersData[$index]["full_name"];
echo $user_name;

if (isset($_POST['reserv'])) {
  reservLocker($_POST['lockerId']);
}
function reservLocker($lockerId)
{
  global $conn, $user_id;
  updateData($conn, 'lockers', ['status' => 'reserved'], 'locker_id', $lockerId);
  updateData($conn, 'users', ['locker_id' => $lockerId], 'user_id', $user_id);
  $_SESSION['has_locker'] = true;
  header("Location: locker_details.php");
}

if (isset($_POST['logout'])) {
  session_destroy();
  header("location:login.php");
}

?>
<form class="list-support" method="post">
  <button type="submit" name="logout">
    <i class="fa-solid fa-arrow-up-from-bracket fa-rotate-270"></i>
  </button>
</form>
<section class="home locker_home min-vh-100">
  <div class="container">
    <div class="row gx-0">
      <?php foreach ($lockersData as $locker) {
        if ($locker["status"] === "avilable") { ?>
          <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="locker bg-success">
              <h3 class="fs-3 mb-0 ms-2"><?php echo $locker["locker_id"] ?></h3>
              <div class="icon fs-4 me-2">
                <i class="fa-solid fa-lock-open"></i>
              </div>
              <div class="">
                <span class="bottom-0" style="width: 125px; margin-bottom: 8px"></span>
                <span class="bottom-0" style="width: 115px; margin-bottom: 16px"></span>
                <span class="bottom-0" style="width: 105px; margin-bottom: 24px"></span>
                <span class="top-0" style="width: 125px; margin-top: 8px"></span>
                <span class="top-0" style="width: 115px; margin-top: 16px"></span>
                <span class="top-0" style="width: 105px; margin-top: 24px"></span>
              </div>
              <div class="content">
                الطابق : <?php echo $locker["floor"] ?> <br />
                الخزانه : غير محجوزه
              </div>
            </div>
            <div>
              <form action='choose_locker.php' method='post'>
                <input type='hidden' name='lockerId' value='<?php echo $locker["locker_id"]; ?>'>
                <button type='submit' name='reserv' class='btn btn-info'>حجز</button>
              </form>
            </div>
          </div>
        <?php }
      } ?>
    </div>
  </div>
</section>

<?php
include ('includes/templates/footer.php');
?>