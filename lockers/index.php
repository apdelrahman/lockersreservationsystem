<?php
session_start();
session_destroy();

include ('includes/db/db.php');
include ('includes/templates/header.php');
include ('includes/templates/navbar.php');
?>
<div class="home homepage min-vh-100">
  <div class="container">
    <div class="welcome bg-dark bg-opacity-50 rounded-5 text-white py-5 px-3">
      <div class="cont">
        <h1>مشروع خزانات إلكترونى <br>خاص بالطلاب</h1>
        <p><span>تحت أشرف : </span> الدكتوره سارة الغنام</p>
        <p>إعداد الطالبات</p>
        <ul>
          <li>حصه الشاوى</li>
          <li>حنان جليح</li>
          <li>بتول العبدلله</li>
          <li>مريم الرزق</li>
          <li>روان العلي</li>
        </ul>
      </div>
      <div class="w-100 d-flex justify-content-center align-items-center gap-3">
        <a class="btn btn-info px-5 fw-bold " href="./login.php">تسجيل الدخول</a>
      </div>
    </div>
  </div>
</div>
<?php
include ('includes/templates/footer.php');
?>