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
            <div class="col-md-12">
                <h5 class="text-center">สวัสดีคุณ <?= $_SESSION['name'] . ' ' . $_SESSION['surname']; ?></h5>
            </div>
            <form method="post">
                <div class="mb-2">
                    <div class="col-sm-9">
                        <select name="course_code" id="course_code" class="form-control" onchange="updateCourseName()" required>
                            <option value="">เลือกวิชา</option>
                            <?php
                            require_once 'connect.php';

                            $sql = "SELECT * FROM tb_courses";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $courseCode = $row['tb_course_code'];
                                $courseName = $row['tb_course_name'];
                                echo "<option value='$courseCode'>$courseCode - $courseName</option>";
                            }
                            ?>
                        </select>
                        <script>
                            function updateCourseName() {
                                var selectElement = document.getElementById("course_code");
                                var selectedOption = selectElement.options[selectElement.selectedIndex];

                                var courseName = selectedOption.text.split(" - ")[1].trim();
                                document.getElementById("course_name").value = courseName;
                            }
                        </script>
                    </div>
                </div>
                <div class="mb-2">
                    <div class="col-sm-9">
                        <select name="degree" class="form-control" required>
                            <option value="">เลือกระดับชั้น</option>
                            <?php
                            require_once 'connect.php';

                            $sql = "SELECT * FROM tb_rooms";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $roomId = $row['tb_room_id'];
                                $roomName = $row['tb_room_name'];
                                echo "<option value='$roomId'>$roomName</option>";
                            }
                            ?>
                        </select>

                    </div>
                </div>

                <div>
                    <input type="hidden" name="course_name" id="course_name" class="form-control">
                    <input type="hidden" name="teacher_id" class="form-control" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="name" class="form-control" value="<?php echo $_SESSION['name']; ?>">
                    <input type="hidden" name="surname" class="form-control" value="<?php echo $_SESSION['surname']; ?>">
                </div>
                <div class="d-grid gap-2 col-sm-9 mb-3">
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
            </form>
        </div>
    </div>
</body>

</html>