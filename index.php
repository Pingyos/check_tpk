<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check_Tpk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="shaselected_class384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container ">
        <form method="post">

            <div class="row">
                <!-- วันที่ -->
                <div class="col-lg-6 col-md-6 col-12">
                    <label for="current_time" class="form-label">วันที่</label>
                    <input type="date" class="form-control" id="current_time" name="current_time">
                </div>
                <script>
                    var currentDateInput = document.getElementById('current_time');
                    var currentDate = new Date();
                    var year = currentDate.getFullYear();
                    var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                    var day = ("0" + currentDate.getDate()).slice(-2);
                    currentDateInput.value = year + "-" + month + "-" + day;
                </script>
                <!-- วันที่ -->

                <!-- คาบ -->
                <div class="col-lg-6 col-md-6 col-12">
                    <label for="period" class="form-label">คาบ</label>
                    <div class="checkbox-group">

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
                <!-- คาบ -->

                <!-- ชั้น/ห้อง -->
                <div class="col-lg-6 col-md-6 col-12">
                    <label for="class" class="form-label">ชั้น/ห้อง</label>
                    <select id="class" name="class" class="form-select">
                        <option value="1">ม.1</option>
                        <option value="2">ม.2</option>
                        <option value="3">ม.3</option>
                        <option value="4">ม.4</option>
                        <option value="5">ม.5</option>
                        <option value="6">ม.6</option>
                    </select>
                </div>
                <!-- ชั้น/ห้อง -->

                <!-- วิชา -->
                <?php
                require_once 'connection.php';

                // ตรวจสอบค่า tb_teacher_id ที่ส่งมาจาก URL
                if (isset($_GET['tb_teacher_id'])) {
                    $tb_teacher_id = $_GET['tb_teacher_id'];

                    // ดึงข้อมูลจากตาราง tb_courses ที่มี tb_teacher_id ตรงกับ tb_teacher_id ที่ส่งมาจาก URL
                    $sql = "SELECT tb_course_name, tb_course_code, tb_course_comment FROM tb_courses WHERE tb_teacher_id = :tb_teacher_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':tb_teacher_id', $tb_teacher_id);
                    $stmt->execute();

                    // ตรวจสอบว่ามีข้อมูลหรือไม่
                    if ($stmt->rowCount() > 0) {
                        echo '<div class="col-lg-6 col-md-6 col-12">';
                        echo '<label for="subject" class="form-label">วิชา</label>';
                        echo '<select id="subject" name="subject" class="form-select" onchange="onSubjectSelected()">';
                        echo '<option value="">โปรดเลือกวิชา</option>';

                        // วนลูปเพื่อสร้างตัวเลือกใน dropdown
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $tb_course_name = $row["tb_course_name"];
                            $tb_course_code = $row["tb_course_code"];
                            $tb_course_comment = $row["tb_course_comment"];

                            echo '<option value="' . $tb_course_code . ' - ' . $tb_course_name . '">' . $tb_course_code . ' - ' . $tb_course_name . ' - ' . $tb_course_comment . '  </option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    } else {
                        echo 'ไม่พบข้อมูลวิชา';
                    }
                } else {
                    echo 'ไม่พบรหัสครูผู้สอนใน URL';
                }
                ?>
                <!-- วิชา -->
                <input type="hidden" name="tb_teacher_id" id="tb_teacher_id" value="<?php echo $tb_teacher_id; ?>">
            </div>
            <hr>
            <!-- นักเรียน -->
            <div class="row mt-4">
                <div class="col-lg-6 col-md-6 col-12">
                    <table id="student-table" class="table table-striped table-bordered">
                        <thead>

                        </thead>
                        <tbody id="student-data">
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                function onSubjectSelected() {
                    var selectedSubject = document.getElementById("subject").value;
                    if (selectedSubject !== "") {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                document.getElementById("student-data").innerHTML = xhr.responseText;
                            }
                        };
                        xhr.open("POST", "get_students.php", true);
                        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xhr.send("subject=" + selectedSubject);
                    }
                }
            </script>
            <!-- นักเรียน -->
            <hr>
            <button type="submit" class="btn btn-primary">ส่งข้อมูล</button>
            <?php
            require_once 'db_add.php';
            // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //     echo '<pre>';
            //     print_r($_POST);
            //     echo '</pre>';
            // }
            ?>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</html>