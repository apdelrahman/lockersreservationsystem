<?php
// Start or resume the session
session_start();

// Include database connection
include_once 'includes/db/db.php';

// Include header
include_once 'includes/templates/header.php';

// Include navbar
include_once 'includes/templates/navbar.php';
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
        <a class="btn btn-info px-5 fw-bold" href="./login.php">تسجيل الدخول</a>
      </div>
    </div>
  </div>
</div>
<?php
// Include footer
include_once 'includes/templates/footer.php';
?>
