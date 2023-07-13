<?php
session_start();
echo '
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
//เช็คว่ามีตัวแปร session อะไรบ้าง
//print_r($_SESSION);
//exit();
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
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
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
                        <input type="date" name="time" id="time" class="form-control" required>
                    </div>
                    <script>
                        var currentDateInput = document.getElementById('time');
                        var currentDate = new Date();
                        var year = currentDate.getFullYear();
                        var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                        var day = ("0" + currentDate.getDate()).slice(-2);
                        currentDateInput.value = year + "-" + month + "-" + day;
                    </script>
                </div>
                <div class="mb-2">
                    <div class="col-sm-9">
                        <input type="checkbox" class="checkbox" name="period[]" value="1" id="period1">
                        <label for="period1">คาบที่ 1</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="2" id="period2">
                        <label for="period2">คาบที่ 2</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="3" id="period3">
                        <label for="period3">คาบที่ 3</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="4" id="period4">
                        <label for="period4">คาบที่ 4</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="5" id="period5">
                        <label for="period5">คาบที่ 5</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="6" id="period6">
                        <label for="period6">คาบที่ 6</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="7" id="period7">
                        <label for="period7">คาบที่ 7</label>

                        <input type="checkbox" class="checkbox" name="period[]" value="8" id="period8">
                        <label for="period8">คาบที่ 8</label>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="col-sm-9">
                        <select name="courses" required class="form-control">
                            <option value="">เลือกวิชา</option>
                            <?php
                            require_once 'connect.php';

                            // ตรวจสอบว่ามีค่า $_SESSION['id'] หรือไม่
                            if (isset($_SESSION['id'])) {
                                $teacherId = $_SESSION['id'];

                                // สร้างคำสั่ง SQL เพื่อเรียกข้อมูล course_code จากตาราง tb_reg_courses
                                $sql = "SELECT course_code FROM tb_reg_courses WHERE teacher_id = :teacherId";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':teacherId', $teacherId);
                                $stmt->execute();

                                // วนลูปแสดงผลตัวเลือก
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $courseCode = $row['course_code'];
                                    echo "<option value='$courseCode'>$courseCode</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class=" mb-2">
                    <div class="col-sm-9">
                        <select name="degree" class="form-control">
                            <option value="">เลือกระดับชั้น</option>
                        </select>
                    </div>
                </div>
                <div>
                    <input type="hidden" name="teacher_id" class="form-control" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="name" class="form-control" value="<?php echo $_SESSION['name']; ?>">
                    <input type="hidden" name="surname" class="form-control" value="<?php echo $_SESSION['surname']; ?>">
                </div>
                <div class="d-grid gap-2 col-sm-9 mb-3">
                    <button type="submit" class="btn btn-primary">แสดงรายชื่อ</button>
                    <?php
                    require_once 'add_checking_db.php';
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</html>