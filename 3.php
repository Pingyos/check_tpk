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
                                            <h3 class="text-center">รายงานการขาดเรียน</h3>
                                        </div>
                                        <hr>
                                        <form action="#" method="post" novalidate="novalidate">
                                            <div class="row">
                                                <?php
                                                require_once 'connect.php';

                                                // ใช้ PDO เพื่อดึงข้อมูลวิชาจากฐานข้อมูล
                                                $sql = "SELECT DISTINCT courses, course_name FROM ck_checking";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $checkings = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                // สร้าง dropdown
                                                echo '<div class="form-group col-12">';
                                                echo '<label for="course" class="control-label mb-1">วิชา</label>';
                                                echo '<select name="course" id="course" class="form-control">';
                                                echo '<option value="" selected>แสดงทั้งหมด</option>'; // เพิ่มตัวเลือก "แสดงทั้งหมด"

                                                $selectedCourses = array(); // ตัวแปรเก็บรายการวิชาที่ถูกเลือกไว้แล้ว

                                                foreach ($checkings as $checking) {
                                                    $courseCode = $checking['courses'];
                                                    $courseName = $checking['course_name'];

                                                    // เพิ่มตัวเลือกเฉพาะเมื่อยังไม่มีรายการวิชานี้อยู่ในรายการที่ถูกเลือกไว้แล้ว
                                                    if (!in_array($courseCode, $selectedCourses)) {
                                                        $selected = ($courseCode == $_POST['course']) ? 'selected' : ''; // ตรวจสอบว่าตรงกับตัวเลือกก่อนหน้าหรือไม่
                                                        echo '<option value="' . $courseCode . '" ' . $selected . '>' . $courseName . '</option>';
                                                        $selectedCourses[] = $courseCode; // เพิ่มรายการวิชาที่ถูกเลือกไว้ในรายการ
                                                    }
                                                }
                                                echo '</select>';
                                                echo '</div>';

                                                // เช็คว่ามีค่าวันที่เริ่มต้นที่ส่งมาหรือไม่ ถ้าไม่มีกำหนดให้เป็นวันที่ปัจจุบัน
                                                $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');

                                                // เช็คว่ามีค่าวันที่สิ้นสุดที่ส่งมาหรือไม่ ถ้าไม่มีกำหนดให้เป็นวันที่ปัจจุบัน
                                                $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

                                                // แปลงวันที่เริ่มต้นและวันที่สิ้นสุดเป็นวัตถุ DateTime
                                                $startDateObj = new DateTime($startDate);
                                                $endDateObj = new DateTime($endDate);

                                                // ลดวันที่เริ่มต้นลง 1 วัน
                                                $startDateObj->modify('-1 day');

                                                // แปลงกลับเป็นรูปแบบของวันที่
                                                $startDate = $startDateObj->format('Y-m-d');

                                                // เช็คว่ามีค่ารหัสนักเรียนที่ส่งมาหรือไม่
                                                $studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

                                                // เพิ่ม input date สำหรับเลือกวันที่เริ่มต้น
                                                echo '<div class="form-group col-6">';
                                                echo '<label for="startDate" class="control-label mb-1">วันที่เริ่มต้น</label>';
                                                echo '<input type="date" name="startDate" id="startDate" class="form-control" value="' . $startDate . '">';
                                                echo '</div>';

                                                // เพิ่ม input date สำหรับเลือกวันที่สิ้นสุด
                                                echo '<div class="form-group col-6">';
                                                echo '<label for="endDate" class="control-label mb-1">วันที่สิ้นสุด</label>';
                                                echo '<input type="date" name="endDate" id="endDate" class="form-control" value="' . $endDate . '">';
                                                echo '</div>';

                                                // เพิ่ม input text สำหรับค้นหารหัสนักเรียน
                                                echo '<div class="form-group col-12">';
                                                echo '<label for="studentCode" class="control-label mb-1">รหัสนักเรียน</label>';
                                                echo '<input type="text" name="studentCode" id="studentCode" class="form-control" value="' . $studentCode . '">';
                                                echo '</div>';

                                                if (isset($_POST['course']) || isset($_POST['startDate']) || isset($_POST['endDate']) || isset($_POST['studentCode'])) {
                                                    $selectedCourse = $_POST['course'];
                                                    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');
                                                    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');
                                                    $studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

                                                    // เชื่อมต่อฐานข้อมูลอีกครั้ง
                                                    require_once 'connect.php';

                                                    $sql = "SELECT c.*, s.tb_student_name, s.tb_student_sname FROM ck_checking c 
                JOIN ck_students s ON c.absent = s.tb_student_code
                WHERE 1=1";

                                                    if ($selectedCourse) {
                                                        $sql .= " AND c.courses = :courseCode";
                                                    }

                                                    if ($startDate && $endDate) {
                                                        $sql .= " AND DATE(c.time) BETWEEN :startDate AND :endDate";
                                                    }

                                                    if ($studentCode) {
                                                        $sql .= " AND c.absent = :studentCode";
                                                    }

                                                    $stmt = $conn->prepare($sql);

                                                    if ($selectedCourse) {
                                                        $stmt->bindParam(':courseCode', $selectedCourse);
                                                    }

                                                    if ($startDate && $endDate) {
                                                        $stmt->bindParam(':startDate', $startDate);
                                                        $stmt->bindParam(':endDate', $endDate);
                                                    }

                                                    if ($studentCode) {
                                                        $stmt->bindParam(':studentCode', $studentCode);
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
                                                            <th>ตาบเรียน/วันที่</th>
                                                        </tr></thead>';
                                                        echo '<tbody>';

                                                        foreach ($students as $student) {
                                                            echo '<tr>';
                                                            echo '<td>' . $student['absent'] . '</td>';
                                                            echo '<td>' . $student['tb_student_name'] . ' ' . $student['tb_student_sname'] . '</td>';
                                                            echo '<td>' . $student['cause'] . '</td>';
                                                            echo '<td>';

                                                            $level = $student['rooms'];
                                                            $class = ($level - 1) % 3 + 1;
                                                            $year = floor(($level - 1) / 3) + 1;
                                                            echo 'ม.' . $year . '/' . $class;

                                                            echo '</td>';
                                                            echo '<td>' . $student['courses'] . ' - ' . $student['course_name'] . '</td>';
                                                            echo '<td>' . $student['period'] . ' / ' . $student['time'] . '</td>';
                                                            echo '</tr>';
                                                        }

                                                        echo '</tbody>';
                                                        echo '</table>';
                                                    } else {
                                                        echo 'ไม่มีข้อมูลนักเรียนที่ขาด.';
                                                    }

                                                    // ปิดการเชื่อมต่อฐานข้อมูล
                                                    $conn = null;
                                                }
                                                ?>
                                            </div>
                                            <hr>
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="row" style="margin-left : 0px">
                                                        <button type="submit" class="btn btn-info">
                                                            <span><i class="menu-icon fa fa-search"></i> แสดงรายชื่อ</span>
                                                        </button>
                                                        &nbsp;
                                                        <button type="button" class="btn btn-secondary" onclick="clearForm()">
                                                            <span>เคลียร์ข้อมูล</span>
                                                        </button>
                                                    </div>
                                                    <script>
                                                        function clearForm() {
                                                            document.getElementById('course').selectedIndex = 0;
                                                            document.getElementById('startDate').value = '';
                                                            document.getElementById('endDate').value = '';
                                                            document.getElementById('studentCode').value = '';
                                                        }
                                                    </script>

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