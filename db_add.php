<?php
if (
    isset($_POST['current_time'])
    && isset($_POST['period'])
    && isset($_POST['subject'])
    && isset($_POST['tb_teacher_id'])
    && isset($_POST['absent'])
    && isset($_POST['class'])
) {
    //ไฟล์เชื่อมต่อฐานข้อมูล
    require_once 'connection.php';

    // ตรวจสอบจำนวนข้อมูลในตัวแปร absent
    $absentCount = count($_POST['absent']);

    // SQL insert
    $stmt = $conn->prepare("INSERT INTO tb_users_logs
    (`current_time`,
    `period`,
    `subject`,
    `tb_teacher_id`,
    `class`,
    `absent`)
    VALUES
    (:current_time,
    :period,
    :subject,
    :tb_teacher_id,
    :class,
    :absent)");

    // Bind parameter values
    $stmt->bindParam(':current_time', $_POST['current_time'], PDO::PARAM_STR);
    $stmt->bindParam(':class', $_POST['class'], PDO::PARAM_STR);
    $stmt->bindParam(':subject', $_POST['subject'], PDO::PARAM_STR);
    $stmt->bindParam(':tb_teacher_id', $_POST['tb_teacher_id'], PDO::PARAM_STR);
    $stmt->bindValue(':period', implode(',', $_POST['period']), PDO::PARAM_STR);

    // เพิ่มแถวใหม่เมื่อ absent มีข้อมูลมากกว่า 1 ตัว
    if ($absentCount > 1) {
        foreach ($_POST['absent'] as $absentItem) {
            $stmt->bindValue(':absent', $absentItem, PDO::PARAM_STR);
            $stmt->execute();
        }
    } else {
        $absentValue = implode(',', $_POST['absent']);
        $stmt->bindValue(':absent', $absentValue, PDO::PARAM_STR);
        $stmt->execute();
    }

    $tb_teacher_id = $_POST['tb_teacher_id']; // เปลี่ยนจาก $user['tb_teacher_id'] เป็น $_POST['tb_teacher_id']

    // เงื่อนไขตรวจสอบการเพิ่มข้อมูล
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
    echo '<script>
    swal({
        title: "บันทึกข้อมูลสำเร็จ", 
        text: "กรุณารอสักครู่",
        type: "success", 
        timer: 2000, 
        showConfirmButton: false 
    }, function(){
        window.location.href = "index.php?tb_teacher_id=' . $tb_teacher_id . '";
    });
    </script>';
} else {
    echo '<script>
    swal({
        title: "เกิดข้อผิดพลาด",
        type: "error"
    }, function() {
        window.location = "index.php";
    });
    </script>';
}
