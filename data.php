<?php
session_start();
echo '
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
//เช็คว่ามีตัวแปร session อะไรบ้าง
// print_r($_SESSION);
// exit();
//สร้างเงื่อนไขตรวจสอบสิทธิ์การเข้าใช้งานจาก session
if (empty($_SESSION['id']) && empty($_SESSION['name']) && empty($_SESSION['surname'])) {
    echo '<script>
                setTimeout(function() {
                swal({
                title: "คุณไม่มีสิทธิ์ใช้งานหน้านี้",
                type: "error"
                }, function() {
                window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                });
                }, 1000);
                </script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12"> <br>
            </div>
            <?php
            require_once 'connect.php';

            if (isset($_SESSION['id'])) {
                $teacherId = $_SESSION['id'];

                $stmt = $conn->prepare("SELECT * FROM tb_main WHERE teacher_id = :teacherId");
                $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $teacherName = $row['name'];
                }
            }
            ?>
            <div class="col-md-12">
                <h5 class="text-center">สวัสดีคุณ <?= $_SESSION['name'] . ' ' . $_SESSION['surname']; ?></h5>
                <h5><?= $row['name']; ?> <?= $row['surname']; ?></h5>
                <h5><?= $row['time']; ?> <?= $row['period']; ?></h5>
                <h5><?= $row['courses']; ?> <?= $row['course_name']; ?></h5>
            </div>

            <form method="post">


            </form>
            <div class="d-grid gap-2 col-sm-12 mb-3">
                <button type="submit" class="btn btn-primary">ยืนยัน</button>
                <?php
                require_once 'add_student_db.php';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo '<pre>';
                    print_r($_POST);
                    echo '</pre>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>