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

                if (isset($_GET['id'])) {
                    $id = $_GET['id'];

                    $stmt = $conn->prepare("SELECT * FROM tb_main WHERE teacher_id = :teacherId AND id = :id");
                    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $teacherName = $row['name'];
                    }
                }
            }
            ?>
            <div class="col-md-12">
                <h5 class="text-center">สวัสดีคุณ <?= $_SESSION['name'] . ' ' . $_SESSION['surname']; ?></h5>
                <h5><?= $row['id']; ?></h5>
                <h5><?= $row['rooms']; ?></h5>
                <h5><?= $row['name']; ?> <?= $row['surname']; ?></h5>
                <h5><?= $row['time']; ?> <?= $row['period']; ?></h5>
                <h5><?= $row['courses']; ?> <?= $row['course_name']; ?></h5>
            </div>

            <form method="post">
                <?php
                require_once 'connect.php';

                if (isset($_SESSION['id']) && isset($_GET['id'])) {
                    $teacherId = $_SESSION['id'];
                    $id = $_GET['id'];

                    $stmt = $conn->prepare("SELECT * FROM tb_main WHERE teacher_id = :teacherId AND id = :id");
                    $stmt->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $rooms = $row['rooms'];

                        $stmt2 = $conn->prepare("SELECT * FROM tb_students WHERE tb_student_degree = :rooms");
                        $stmt2->bindParam(':rooms', $rooms, PDO::PARAM_INT);
                        $stmt2->execute();

                        if ($stmt2->rowCount() > 0) {
                            $countrow = 1;
                ?>
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>Student Code</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>check box</th>
                                        <th>input text</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><?= $countrow ?></td>
                                            <td><?= $row2['tb_student_code']; ?></td>
                                            <td><?= $row2['tb_student_name']; ?></td>
                                            <td><?= $row2['tb_student_sname']; ?></td>
                                            <td>
                                                <input type="checkbox" name="absent[]" value="<?= $row2['tb_student_code']; ?>" onchange="handleCheckbox(this)">
                                            </td>
                                            <td>
                                                <input type="text" name="cause[]" value="" disabled>
                                            </td>
                                        </tr>

                                        <script>
                                            function handleCheckbox(checkbox) {
                                                var inputText = checkbox.parentNode.nextElementSibling.querySelector('input[type="text"]');
                                                if (checkbox.checked) {
                                                    inputText.disabled = false;
                                                } else {
                                                    inputText.disabled = true;
                                                    inputText.value = '';
                                                }
                                            }
                                        </script>

                                    <?php $countrow++;
                                    } ?>
                                </tbody>

                            </table>
                <?php } else {
                            echo 'No student data found.';
                        }
                    } else {
                        echo 'No data found.';
                    }
                }
                ?>

                <div>
                    <input type="hidden" name="time" class="form-control" value="<?php echo $row['time']; ?>">
                    <input type="hidden" name="period" class="form-control" value="<?php echo $row['period']; ?>">
                    <input type="hidden" name="courses" class="form-control" value="<?php echo $row['courses']; ?>">
                    <input type="hidden" name="course_name" class="form-control" value="<?php echo $row['course_name']; ?>">
                    <input type="hidden" name="rooms" class="form-control" value="<?php echo $row['rooms']; ?>">
                    <input type="hidden" name="teacher_id" class="form-control" value="<?php echo $row['teacher_id']; ?>">
                    <input type="hidden" name="name" class="form-control" value="<?php echo $row['name']; ?>">
                    <input type="hidden" name="surname" class="form-control" value="<?php echo $row['surname']; ?>">
                </div>
                <div class="d-grid gap-2 col-sm-12 mb-3">
                    <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    <?php
                    // require_once 'add_student_db.php';
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        echo '<pre>';
                        print_r($_POST);
                        echo '</pre>';
                    }
                    ?>
                </div>
            </form>
        </div>
    </div>
    </div>
</body>

</html>