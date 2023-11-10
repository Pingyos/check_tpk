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
                                            <h3 class="text-center">--</h3>
                                        </div>
                                        <hr>
                                        <form method="post" novalidate="novalidate">
                                            <div class="row">
                                                <?php
                                                require_once 'connect.php';
                                                $sql = "SELECT DISTINCT courses, course_name FROM ck_checking ";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $checkings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');

                                                $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');


                                                $startDateObj = new DateTime($startDate);
                                                $endDateObj = new DateTime($endDate);


                                                $startDateObj->modify('-1 day');

                                                $startDate = $startDateObj->format('Y-m-d');


                                                $studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

                                                echo '<div class="form-group col-6">';
                                                echo '<label for="startDate" class="control-label mb-1">วันที่เริ่มต้น</label>';
                                                echo '<input type="date" name="startDate" id="startDate" class="form-control" value="' . $startDate . '">';
                                                echo '</div>';


                                                echo '<div class="form-group col-6">';
                                                echo '<label for="endDate" class="control-label mb-1">วันที่สิ้นสุด</label>';
                                                echo '<input type="date" name="endDate" id="endDate" class="form-control" value="' . $endDate . '">';
                                                echo '</div>';



                                                if (isset($_POST['startDate']) || isset($_POST['endDate'])) {
                                                    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');
                                                    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');

                                                    require_once 'connect.php';

                                                    $sql = "SELECT absent, COUNT(absent) as count FROM ck_checking WHERE 1=1 ";

                                                    if ($startDate && $endDate) {
                                                        $sql .= " AND DATE(time) BETWEEN :startDate AND :endDate";
                                                    }
                                                    $sql .= " GROUP BY absent";

                                                    $stmt = $conn->prepare($sql);

                                                    if ($startDate && $endDate) {
                                                        $stmt->bindParam(':startDate', $startDate);
                                                        $stmt->bindParam(':endDate', $endDate);
                                                    }

                                                    $stmt->execute();
                                                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                    if (count($students) > 0) {
                                                        echo '<table id="bootstrap-data-table" class="table table-striped table-bordered">';
                                                        echo '<thead><tr>
                                                            <th>รหัสนักเรียน</th>
                                                            <th>จำนวน</th>
                                                        </tr></thead>';
                                                        echo '<tbody>';

                                                        foreach ($students as $student) {
                                                            echo '<tr>';
                                                            echo '<td>' . $student['absent'] . '</td>';
                                                            echo '<td>' . $student['count'] . '</td>';
                                                            echo '</tr>';
                                                        }
                                                        echo '</tbody>';
                                                        echo '</table>';
                                                    } else {
                                                        echo 'ไม่มีข้อมูลนักเรียนที่ขาด.';
                                                    }
                                                    $conn = null;
                                                }

                                                ?>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-info">
                                                <span><i class="menu-icon fa fa-search"></i> แสดงรายชื่อ</span>
                                            </button>
                                            <button type="button" id="export_data" class="btn btn-success">Export</button>
                                            <script>
                                                document.getElementById('export_data').addEventListener('click', function() {
                                                    var startDate = document.querySelector('#exportForm input[name="startDate"]').value;
                                                    var endDate = document.querySelector('#exportForm input[name="endDate"]').value;
                                                    var url = `exportpdf1.php?startDate=${startDate}&endDate=${endDate}`;
                                                    url += `&timestamp=${Date.now()}`;
                                                    window.open(url, '_blank');
                                                });
                                            </script>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <?php require_once 'footer.php'; ?>
        <!-- /.site-footer -->
    </div>
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