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
                        <!-- HTML -->
                        <select name="courses" class="form-control" onchange="updateClassDropdown(this.value)">
                            <option value="">เลือกวิชา</option>
                            <?php
                            require_once 'connect.php';

                            // ตรวจสอบว่ามีการเข้าสู่ระบบแล้วด้วย $_SESSION
                            if (isset($_SESSION['id'])) {
                                $teacherId = $_SESSION['id'];

                                $sql = "SELECT * FROM tb_reg_courses WHERE teacher_id = :teacherId";
                                $stmt = $conn->prepare($sql);
                                $stmt->bindParam(':teacherId', $teacherId);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $course = $row['courses'];
                                    echo "<option value='$course'>$course</option>";
                                }
                            }
                            ?>
                        </select>

                        <select name="class" class="form-control">
                            <option value="">เลือกระดับชั้น</option>
                        </select>

                        <script>
                            function updateClassDropdown(selectedCourse) {
                                var classDropdown = document.querySelector('select[name="class"]');
                                classDropdown.innerHTML = '<option value="">กำลังโหลดข้อมูล...</option>';

                                axios.get('get_rooms.php', {
                                        params: {
                                            course: selectedCourse
                                        }
                                    })
                                    .then(function(response) {
                                        // เมื่อรับข้อมูลสำเร็จ
                                        classDropdown.innerHTML = ''; // เคลียร์ตัวเลือกเดิม

                                        if (response.data.length > 0) {
                                            // สร้างตัวเลือกห้องเรียนจากข้อมูลที่ได้รับ
                                            response.data.forEach(function(room) {
                                                classDropdown.innerHTML += '<option value="' + room + '">' + room + '</option>';
                                            });
                                        } else {
                                            classDropdown.innerHTML = '<option value="">ไม่พบห้องเรียน</option>';
                                        }
                                    })
                                    .catch(function(error) {
                                        classDropdown.innerHTML = '<option value="">เกิดข้อผิดพลาดในการดึงข้อมูล</option>';
                                    });
                            }
                        </script>
                    </div>
                </div>
                <div class="students-box">
                    <h2>รายชื่อนักเรียน</h2>
                    <ul id="students-list"></ul>
                </div>

                <script>
                    function loadStudents(selectedCourse) {
                        var studentsList = document.getElementById('students-list');
                        studentsList.innerHTML = '<li>Loading...</li>';

                        axios.get('get_rooms.php', {
                                params: {
                                    course: selectedCourse
                                }
                            })
                            .then(function(response) {
                                studentsList.innerHTML = ''; // เคลียร์รายการเดิม

                                response.data.forEach(function(student) {
                                    var li = document.createElement('li');
                                    li.textContent = student;
                                    studentsList.appendChild(li);
                                });
                            })
                            .catch(function(error) {
                                studentsList.innerHTML = '<li>Error loading students.</li>';
                                console.error(error);
                            });
                    }

                    var courseDropdown = document.querySelector('select[name="courses"]');
                    courseDropdown.addEventListener('change', function() {
                        var selectedCourse = courseDropdown.value;
                        loadStudents(selectedCourse);
                    });
                </script>

                <div>
                    <input type="hidden" name="teacher_id" class="form-control" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="name" class="form-control" value="<?php echo $_SESSION['name']; ?>">
                    <input type="hidden" name="surname" class="form-control" value="<?php echo $_SESSION['surname']; ?>">
                </div>
                <div class="d-grid gap-2 col-sm-9 mb-3">
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
</body>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</html>