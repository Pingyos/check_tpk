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
                                            <h3 class="text-center">นำเข้าข้อมูล</h3>
                                        </div>
                                        <hr>
                                        <form action="#" method="post" novalidate="novalidate" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group col-lg-6 col-md-3 col-12">
                                                    <div class="col col-md-3"><label for="excel" class=" form-control-label">เพิ่มไฟล์</label></div>
                                                    <div class="col-12 col-md-9"><input type="file" id="excel" name="excel" class="form-control-file"></div>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-3 col-12">
                                                <button name="import" type="submit" class="btn btn-info">
                                                    <span>อัพโหลด</span>
                                                </button>
                                            </div>
                                        </form>
                                        <hr>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <button type="button" class="btn btn-info ml-auto" data-toggle="modal" data-target="#teachersModal">
                                                <i class="fa fa-plus-square-o"></i> เพิ่ม 1 คน
                                            </button>
                                        </div>
                                        <?php require 'config.php'; ?>
                                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                            <tr>
                                                <td>ลำดับ</td>
                                                <td>Email</td>
                                                <td>Password</td>
                                                <td>ชื่อ-สกุล</td>
                                                <td>รายละเอียด</td>
                                            </tr>
                                            <?php
                                            $i = 1;
                                            $rows = mysqli_query($conn, "SELECT * FROM ck_users");
                                            foreach ($rows as $row) :
                                            ?>
                                                <tr>
                                                    <td> <?php echo $i++; ?> </td>
                                                    <td> <?php echo $row["email"]; ?> </td>
                                                    <td> <?php echo $row["password"]; ?> </td>
                                                    <td> <?php echo $row["name_title"]; ?> <?php echo $row["name"]; ?> <?php echo $row["surname"]; ?> </td>
                                                    <td> <a href="del.php?excel_id=<?= $row['excel_id']; ?>" class="btn btn-info"><i class="fa fa-trash-o"></i> Del</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        <?php
                                        if (isset($_POST["import"])) {
                                            $fileName = $_FILES["excel"]["name"];
                                            $fileExtension = explode('.', $fileName);
                                            $fileExtension = strtolower(end($fileExtension));
                                            $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

                                            $targetDirectory = "uploads/" . $newFileName;
                                            move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

                                            error_reporting(0);
                                            ini_set('display_errors', 0);

                                            require 'excelReader/excel_reader2.php';
                                            require 'excelReader/SpreadsheetReader.php';

                                            $reader = new SpreadsheetReader($targetDirectory);
                                            foreach ($reader as $key => $row) {
                                                $id = $row[0];
                                                $email = $row[1];
                                                $password = $row[2];
                                                $name = $row[3];
                                                $surname = $row[4];
                                                $status = $row[5];
                                                mysqli_query($conn, "INSERT INTO ck_users VALUES('', '$id','$email', '$password', '$name', '$surname', '$status')");
                                            }
                                            echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
                                            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
                                            echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
                                            echo '<script>
                                            $(document).ready(function(){
                                                swal({
                                                    title: "นำเข้าข้อมูลสำเร็จ",
                                                    text: "กรุณารอสักครู่",
                                                    type: "success",
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                }, function(){
                                                    window.location.href = "import.php";
                                                });
                                            });
                                        </script>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .card -->
                    </div>
                </div>
                <div class="modal fade" id="teachersModal" tabindex="-1" role="dialog" aria-labelledby="teachersModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="teachersModalLabel">เพิ่มรายชื่อครู</h5>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="id" class="control-label mb-1">รหัสประจำตัว</label>
                                            <input type="text" name="id" id="id" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="email" class="control-label mb-1">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="password" class="control-label mb-1">Password</label>
                                            <input type="text" name="password" id="password" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="name_title" class="control-label mb-1">คำนำหน้า</label>
                                            <input type="text" name="name_title" id="name_title" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="name" class="control-label mb-1">ชื่อ</label>
                                            <input type="text" name="name" id="name" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="surname" class="control-label mb-1">สกุส</label>
                                            <input type="text" name="surname" id="surname" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="position" class="control-label mb-1">ตำแหน่ง</label>
                                            <select name="position" id="position" class="form-control" required>
                                                <option value="0">กรุณาเลือกตำแหน่ง</option>
                                                <option value="ครูผู้ช่วย">ครูผู้ช่วย</option>
                                                <option value="ครู">ครู</option>
                                                <option value="ครูชำนาญการพิเศษ">ครูชำนาญการพิเศษ</option>
                                                <option value="ครูเชี่ยวชาญ">ครูเชี่ยวชาญ</option>
                                                <option value="ครูอัตราจ้าง">ครูอัตราจ้าง</option>
                                                <option value="ผู้อำนวยการ">ผู้อำนวยการ</option>
                                                <option value="รองผู้อำนวยการ">รองผู้อำนวยการ</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-3 col-12">
                                            <label for="groups" class="control-label mb-1">กลุ่มสาระการเรียนรู้</label>
                                            <input type="text" name="groups" id="groups" class="form-control" required>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-3 col-12">
                                            <label for="status" class="control-label mb-1">ตำแหน่ง</label>
                                            <select name="status" id="status" class="form-control" required>
                                                <option value="0">ผู้ใช้งาน</option>
                                                <option value="1">Admin</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <?php
                                        require_once 'add_import_db.php';
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            echo '<pre>';
                                            print_r($_POST);
                                            echo '</pre>';
                                        }
                                        ?>
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </div>
                                </form>
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