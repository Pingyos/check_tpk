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
                                        <!-- เพิ่มโค้ดในส่วนของหน้าเว็บหรืออินเตอร์เฟซที่ต้องการแสดง dropdown วิชา และ input วันที่ -->
                                        <form action="#" method="post" novalidate="novalidate">
                                            <div class="row">
                                                <div class="form-group col-lg-12 col-md-3 col-12">
                                                    <label for="cc-name" class="control-label mb-1">รายงาน</label>
                                                    <fieldset class="form-row" id="Member">

                                                        <input type="radio" id="watch-me" value="yes" name="Member" class="tap-input">
                                                        <label for="watch-me">พิมพ์รายงานการขาดเรียนรายวิชา</label>

                                                        <input type="radio" id="watch-me-maybe" value="maybe" name="Member" class="tap-input">
                                                        <label for="MemberMaybe">พิมพ์รายงานการขาดเรียนรายบุคคล</label>
                                                        <style>
                                                            #MemberNo {
                                                                display: none;
                                                            }
                                                        </style>
                                                        <div>
                                                            <input type="radio" id="MemberNo" value="no" name="Member" class="tap-input">
                                                        </div>

                                                        <!-- พิมพ์รายงานการขาดเรียนรายวิชา -->
                                                        <div id="show-me" class="form-group col-lg-12 col-md-3 col-12">
                                                            <form action="#" method="post" novalidate="novalidate">
                                                                <div class="row">
                                                                    <?php
                                                                    require_once 'connect.php';
                                                                    $teacherId = $_SESSION['id'];
                                                                    $sql = "SELECT DISTINCT courses, course_name FROM ck_checking WHERE teacher_id = :teacherId";
                                                                    $stmt = $conn->prepare($sql);
                                                                    $stmt->bindParam(':teacherId', $teacherId); // Bind the parameter for teacher_id
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
                                                                    echo '<div class="form-group col-lg-6 col-md-3 col-12">';
                                                                    echo '<label for="startDate" class="control-label mb-1">วันที่เริ่มต้น</label>';
                                                                    echo '<input type="date" name="startDate" id="startDate" class="form-control" value="' . $startDate . '">';
                                                                    echo '</div>';

                                                                    // เพิ่ม input date สำหรับเลือกวันที่สิ้นสุด
                                                                    echo '<div class="form-group col-lg-6 col-md-3 col-12">';
                                                                    echo '<label for="endDate" class="control-label mb-1">วันที่สิ้นสุด</label>';
                                                                    echo '<input type="date" name="endDate" id="endDate" class="form-control" value="' . $endDate . '">';
                                                                    echo '</div>';
                                                                    ?>
                                                                </div>
                                                                <hr>
                                                                <div class="col-lg-12">
                                                                    <div class="row">
                                                                        <div class="row" style="margin-left: 0px">
                                                                            <a target="_blank" href="reportpdf.php?teacherId=<?php echo $_SESSION['id']; ?>&course=<?php echo isset($_POST['course']) ? $_POST['course'] : ''; ?>&startDate=<?php echo isset($_POST['startDate']) ? $_POST['startDate'] : ''; ?>&endDate=<?php echo isset($_POST['endDate']) ? $_POST['endDate'] : ''; ?>&studentCode=<?php echo isset($_POST['studentCode']) ? $_POST['studentCode'] : ''; ?>&cause=<?php echo isset($_POST['cause']) ? $_POST['cause'] : ''; ?>" class="btn btn-success" target="_blank" name="exportToPdf">
                                                                                <i class="menu-icon fa fa-file-pdf-o"></i><span> ส่งออก </span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- พิมพ์รายงานการขาดเรียนรายบุคคล -->
                                                        <div id="show-me-2" class="medium-12">
                                                            <div class="form-row">
                                                                <label for="memberNumber2">Please enter your Membership number2</label>
                                                                <input id="memberNumber2" name="memberNumber2" class="inputfield" type="text">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <script>
                                                    function showHide(input) {
                                                        var attrVal = $(input).attr('id');
                                                        switch (attrVal) {
                                                            case 'watch-me':
                                                                $('#show-me-2').hide();
                                                                $('#show-me').show();
                                                                break;
                                                            case "watch-me-maybe":
                                                                $('#show-me').hide();
                                                                $('#show-me-2').show();
                                                                break;
                                                            default:
                                                                $('#show-me-2').hide();
                                                                $('#show-me').hide();
                                                                break;
                                                        }
                                                    }
                                                    $(document).ready(function() {
                                                        $('input[type="radio"]').each(function() {
                                                            showHide(this);
                                                        });
                                                        $('input[type="radio"]').click(function() {
                                                            showHide(this);
                                                        });
                                                    });
                                                </script>
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