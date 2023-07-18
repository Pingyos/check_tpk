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
if (empty($_SESSION['id']) && empty($_SESSION['name']) && empty($_SESSION['surname']) && empty($_SESSION['status'])) {
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

<!doctype html>
<html class="no-js" lang="">
<?php require_once 'head.php'; ?>

<body>
    <!-- Left Panel -->
    <?php require_once 'aside.php'; ?>
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php require_once 'header.php'; ?>
        <!-- /#header -->

        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!-- Widgets  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                        <div class="card-title">
                                            <h3 class="text-center">เช็คชื่อ</h3>
                                        </div>
                                        <hr>
                                        <form action="#" method="post" novalidate="novalidate">
                                            <div class="form-group">
                                                <label for="date" class="control-label mb-1">วันที่</label>
                                                <input type="date" name="time" id="time" class="form-control" required>
                                                <script>
                                                    var currentDateInput = document.getElementById('time');
                                                    var currentDate = new Date();
                                                    var year = currentDate.getFullYear();
                                                    var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                                                    var day = ("0" + currentDate.getDate()).slice(-2);
                                                    currentDateInput.value = year + "-" + month + "-" + day;
                                                </script>
                                            </div>
                                            <div class="form-group">
                                                <label for="cc-name" class="control-label mb-1">คาบเรียน</label>
                                                <div class="form-group has-success">
                                                    <input type="checkbox" class="checkbox" name="period[]" value="1" id="period1">
                                                    <label for="period1">คาบเรียนที่ 1 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="2" id="period2">
                                                    <label for="period2">คาบเรียนที่ 2 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="3" id="period3">
                                                    <label for="period3">คาบเรียนที่ 3 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="4" id="period4">
                                                    <label for="period4">คาบเรียนที่ 4 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="5" id="period5">
                                                    <label for="period5">คาบเรียนที่ 5 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="6" id="period6">
                                                    <label for="period6">คาบเรียนที่ 6 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="7" id="period7">
                                                    <label for="period7">คาบเรียนที่ 7 |</label>

                                                    <input type="checkbox" class="checkbox" name="period[]" value="8" id="period8">
                                                    <label for="period8">คาบเรียนที่ 8</label>
                                                </div>
                                                <?php
                                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                    if (isset($_POST['period'])) {
                                                        $selectedPeriods = $_POST['period'];
                                                        if (count($selectedPeriods) >= 1 && count($selectedPeriods) <= 2) {
                                                        } else {
                                                            echo '<span style="color: red;">กรุณาเลือกคาบเรียนอย่างน้อย 1 คาบเรียน และไม่เกิน 2 คาบเรียน</span>';
                                                        }
                                                    } else {
                                                        echo '<span style="color: red;">กรุณาเลือกคาบเรียนอย่างน้อย 1 คาบเรียน และไม่เกิน 2 คาบเรียน</span>';
                                                    }
                                                }
                                                ?>

                                            </div>


                                            <style>
                                                .error-message {
                                                    color: red;
                                                }
                                            </style>

                                            <div class="form-group">
                                                <label for="cc-number" class="control-label mb-1">วิชา</label>
                                                <select name="courses" required class="form-control">
                                                    <option value="">เลือกวิชา</option>
                                                    <?php
                                                    require_once 'connect.php';

                                                    // ตรวจสอบว่ามีการเข้าสู่ระบบแล้วด้วย $_SESSION
                                                    if (isset($_SESSION['id'])) {
                                                        $teacherId = $_SESSION['id'];

                                                        $sql = "SELECT DISTINCT courses, course_name FROM tb_reg_courses WHERE teacher_id = :teacherId";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->bindParam(':teacherId', $teacherId);
                                                        $stmt->execute();

                                                        $selectedCourses = array(); // ตัวแปรเก็บรายการวิชาที่ถูกเลือกไว้แล้ว

                                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                            $course = $row['courses'];
                                                            $courseName = $row['course_name'];

                                                            // เพิ่มตัวเลือกเฉพาะเมื่อยังไม่มีชื่อวิชานี้อยู่ในรายการที่ถูกเลือกไว้แล้ว
                                                            if (!in_array($course, $selectedCourses)) {
                                                                echo "<option value='$course' data-course-name='$courseName'>$course - $courseName</option>";
                                                                $selectedCourses[] = $course; // เพิ่มรายการวิชาที่ถูกเลือกไว้ในรายการ
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" name="course_name" class="form-control" id="courseNameInput">
                                                <script>
                                                    var coursesDropdown = document.querySelector('select[name="courses"]');
                                                    var courseNameInput = document.getElementById('courseNameInput');

                                                    coursesDropdown.addEventListener('change', function() {
                                                        var selectedOption = this.options[this.selectedIndex];
                                                        var courseName = selectedOption.dataset.courseName;
                                                        courseNameInput.value = courseName;
                                                    });
                                                </script>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="cc-exp" class="control-label mb-1">ระดับชั้น</label>
                                                        <select name="rooms" required class="form-control">
                                                            <option value="">เลือกห้องเรียน</option>
                                                        </select>
                                                    </div>
                                                    <script>
                                                        var coursesDropdown = document.querySelector('select[name="courses"]');
                                                        var roomsDropdown = document.querySelector('select[name="rooms"]');

                                                        coursesDropdown.addEventListener('change', function() {
                                                            var selectedOption = this.options[this.selectedIndex];
                                                            var courseName = selectedOption.dataset.courseName;
                                                            var selectedCourse = this.value;

                                                            // อัปเดตชื่อวิชาใน input course_name
                                                            document.querySelector('input[name="course_name"]').value = courseName;

                                                            // อัปเดตตัวเลือกห้องเรียน
                                                            updateRoomsDropdown(selectedCourse);
                                                        });

                                                        function updateRoomsDropdown(selectedCourse) {
                                                            roomsDropdown.innerHTML = '<option value="">กำลังโหลดข้อมูล...</option>';

                                                            axios.get('get_rooms.php', {
                                                                    params: {
                                                                        course: selectedCourse
                                                                    }
                                                                })
                                                                .then(function(response) {
                                                                    roomsDropdown.innerHTML = ''; // เคลียร์ตัวเลือกเดิม

                                                                    if (response.data.length > 0) {
                                                                        response.data.forEach(function(room) {
                                                                            var roomId = room.id;
                                                                            var roomName = room.name;
                                                                            roomsDropdown.innerHTML += '<option value="' + roomId + '">' + roomName + '</option>';
                                                                        });
                                                                    } else {
                                                                        roomsDropdown.innerHTML = '<option value="">ไม่พบห้องเรียน</option>';
                                                                    }
                                                                })
                                                                .catch(function(error) {
                                                                    roomsDropdown.innerHTML = '<option value="">เกิดข้อผิดพลาดในการดึงข้อมูล</option>';
                                                                });
                                                        }
                                                    </script>
                                                </div>
                                                <div class="col-6">
                                                    <input type="hidden" name="teacher_id" class="form-control" value="<?php echo $_SESSION['id']; ?>">
                                                    <input type="hidden" name="name" class="form-control" value="<?php echo $_SESSION['name']; ?>">
                                                    <input type="hidden" name="surname" class="form-control" value="<?php echo $_SESSION['surname']; ?>">
                                                </div>
                                            </div>
                                            <div>
                                                <?php
                                                require_once 'add_main_db.php';
                                                // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                //     echo '<pre>';
                                                //     print_r($_POST);
                                                //     echo '</pre>';
                                                // }
                                                ?>
                                                <button id="payment-button" type="submit" class="btn btn-info">
                                                    <span id="payment-button-amount">แสดงรายชื่อ</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- .card -->

                    </div>
                </div>
                <!-- /Widgets -->
            </div>
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <?php require_once 'footer.php'; ?>
        <!-- /.site-footer -->
    </div>
    <!-- /#right-panel -->
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

    <!--Chartist Chart-->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
    <script src="assets/js/init/weather-init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="assets/js/init/fullcalendar-init.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>