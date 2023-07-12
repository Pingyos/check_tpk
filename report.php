<!DOCTYPE html>
<html lang="en">
<?php require_once 'head.php'; ?>

<body id="page-top">
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once 'topbar.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800"></h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>วันที่</th>
                                                <th>คาบเรียนที่</th>
                                                <th>ระดับชั้น</th>
                                                <th>วิชา</th>
                                                <th>ครูผู้สอน</th>
                                                <th>ขาดเรียน</th>
                                                <th>สาเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require_once 'connection.php';

                                            if (isset($_GET['tb_teacher_id'])) {
                                                $tb_teacher_id = $_GET['tb_teacher_id'];

                                                $stmt = $conn->prepare("SELECT * FROM tb_users_logs WHERE tb_teacher_id = :tb_teacher_id");
                                                $stmt->bindParam(':tb_teacher_id', $tb_teacher_id);
                                                $stmt->execute();
                                                $result = $stmt->fetchAll();
                                                $result = array_reverse($result);
                                                $countrow = 1;

                                                foreach ($result as $t1) {
                                            ?>
                                                    <!-- ตำแหน่งที่คุณต้องการแสดงข้อมูล -->
                                                    <tr>
                                                        <td><?= $countrow ?></td>
                                                        <td><?= $t1['current_time']; ?></td>
                                                        <td><?= $t1['periods']; ?></td>
                                                        <td><?= $t1['class']; ?></td>
                                                        <td><?= $t1['subject']; ?></td>
                                                        <td><?= $t1['tb_teacher_id']; ?></td>
                                                        <td><?= $t1['absent']; ?></td>
                                                        <td><?= $t1['absence_reason']; ?></td>
                                                    </tr>
                                            <?php
                                                    $countrow++;
                                                }
                                            }
                                            ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <?php require_once 'footer.php'; ?>
        </div>
    </div>
</body>
<?php require_once 'script.php'; ?>

</html>