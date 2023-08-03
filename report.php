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
if (empty($_SESSION['id']) && empty($_SESSION['name']) && empty($_SESSION['surname']) && empty($_SESSION['status'])) {
    echo '<script>
                setTimeout(function() {
                swal({
                title: "Please login again",
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
                                            <h3 class="text-center">รายงานการขาดเรียน</h3>
                                        </div>
                                        <hr>
                                        <form action="#" method="post" novalidate="novalidate">
                                            <div class="row">
                                                <?php
                                                require_once 'connect.php';

                                                // ใช้ PDO เพื่อดึงข้อมูลวิชาจากฐานข้อมูล
                                                $sql = "SELECT DISTINCT courses, course_name FROM ck_checking WHERE teacher_id = :id";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(':id', $_SESSION['id']);
                                                $stmt->execute();
                                                $checkings = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                // สร้าง dropdown สำหรับเลือกวิชา
                                                echo '<div class="form-group col-12">';
                                                echo '<label for="course" class="control-label mb-1">วิชา</label>';
                                                echo '<select name="course" id="course" class="form-control">';
                                                $selectedCourses = array(); // ตัวแปรเก็บรายการวิชาที่ถูกเลือกไว้แล้ว

                                                foreach ($checkings as $checking) {
                                                    $courseCode = $checking['courses'];
                                                    $courseName = $checking['course_name'];

                                                    // เพิ่มตัวเลือกเฉพาะเมื่อยังไม่มีรายการวิชานี้อยู่ในรายการที่ถูกเลือกไว้แล้ว
                                                    if (!in_array($courseCode, $selectedCourses)) {
                                                        echo '<option value="' . $courseCode . '">' . $courseName . '</option>';
                                                        $selectedCourses[] = $courseCode; // เพิ่มรายการวิชาที่ถูกเลือกไว้ในรายการ
                                                    }
                                                }
                                                echo '</select>';
                                                echo '</div>';

                                                // ตรวจสอบว่ามีการส่งค่าวันที่ผ่านฟอร์มหรือไม่
                                                $selectedDate = date('Y-m-d');
                                                if (isset($_POST['date'])) {
                                                    $selectedDate = $_POST['date'];
                                                }

                                                // เพิ่ม input date สำหรับเลือกวันที่
                                                echo '<div class="form-group col-12">';
                                                echo '<label for="date" class="control-label mb-1">วันที่</label>';
                                                echo '<input type="date" name="date" id="date" class="form-control" value="' . $selectedDate . '">';
                                                echo '</div>';

                                                // เมื่อกด submit
                                                if (isset($_POST['course'])) {
                                                    $selectedCourse = $_POST['course'];

                                                    // เชื่อมต่อฐานข้อมูลอีกครั้ง
                                                    require_once 'connect.php';

                                                    // ดึงข้อมูลนักเรียนตามวิชาและวันที่ที่เลือก
                                                    $sql = "SELECT c.*, s.tb_student_tname, s.tb_student_name, s.tb_student_sname, DATE(c.time) AS checking_date 
                                                FROM ck_checking c 
                                                JOIN ck_students s ON c.absent = s.tb_student_code
                                                WHERE c.teacher_id = :teacherId AND c.courses = :courseCode";

                                                    // ตรวจสอบว่ามีการส่งค่าวันที่ผ่านฟอร์มหรือไม่
                                                    if (isset($_POST['date'])) {
                                                        $selectedDate = $_POST['date'];
                                                        $sql .= " AND DATE(c.time) = :selectedDate";
                                                    }

                                                    $stmt = $conn->prepare($sql);
                                                    $stmt->bindParam(':teacherId', $_SESSION['id']);
                                                    $stmt->bindParam(':courseCode', $selectedCourse);

                                                    // ตรวจสอบว่ามีการส่งค่าวันที่ผ่านฟอร์มหรือไม่
                                                    if (isset($_POST['date'])) {
                                                        $stmt->bindParam(':selectedDate', $selectedDate);
                                                    }

                                                    $stmt->execute();
                                                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    if (count($students) > 0) {
                                                        echo '<table id="bootstrap-data-table" class="table table-striped table-bordered">';
                                                        echo '<thead><tr>
                                                                <th>รหัสนักเรียน</th>
                                                                <th>ชื่อ-สกุล</th>
                                                                <th>สาเหตุ</th>
                                                                <th>ระดับชั้น</th>
                                                                <th>วิชา</th>
                                                                <th>คาบเรียน/วันที่</th>
                                                            </tr></thead>';
                                                        echo '<tbody>';
                                                        foreach ($students as $student) {
                                                            echo '<tr>';
                                                            echo '<td>' . $student['absent'] . '</td>';

                                                            // ค้นหาข้อมูลนักเรียนจากรหัสนักเรียนในตาราง ck_students
                                                            $stmt = $conn->prepare("SELECT tb_student_tname, tb_student_name, tb_student_sname FROM ck_students WHERE tb_student_code = :studentCode");
                                                            $stmt->bindParam(':studentCode', $student['absent']);
                                                            $stmt->execute();
                                                            $studentInfo = $stmt->fetch(PDO::FETCH_ASSOC);

                                                            // แสดงชื่อและนามสกุลของนักเรียน
                                                            echo '<td>' . $studentInfo['tb_student_tname'] . ' ' . $studentInfo['tb_student_name'] . ' ' . $studentInfo['tb_student_sname'] . '</td>';
                                                            echo '<td>' . $student['cause'] . '  ' . ($student['custom_cause'] ? '* ' . $student['custom_cause'] : '') . '</td>';
                                                            echo '<td>';

                                                            $level = $student['rooms'];
                                                            $class = ($level - 1) % 3 + 1;
                                                            $year = floor(($level - 1) / 3) + 1;
                                                            echo 'ม.' . $year . '/' . $class;

                                                            echo '</td>';

                                                            echo '<td>' . $student['courses'] . ' - ' . $student['course_name'] . '</td>';
                                                            echo '<td>' . $student['period'] . ' / ' . $student['checking_date'] . '</td>';
                                                            echo '</tr>';
                                                        }
                                                        echo '</tbody>';
                                                        echo '</table>';
                                                    } else {
                                                        echo 'ไม่มีข้อมูลนักเรียนที่ขาดในวันที่ที่เลือก';
                                                    }

                                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                                    $conn = null;
                                                }
                                                ?>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="row" style="margin-left : 0px">
                                                        <button type="submit" class="btn btn-info">
                                                            <span><i class="menu-icon fa fa-search"></i> แสดงรายชื่อ</span>
                                                        </button>
                                                    </div>
                                                </div>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="assets/js/lib/data-table/datatables.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/jszip.min.js"></script>
    <script src="assets/js/lib/data-table/vfs_fonts.js"></script>
    <script src="assets/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.print.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="assets/js/init/datatables-init.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#bootstrap-data-table-export').DataTable();
        });
    </script>
</body>

</html>