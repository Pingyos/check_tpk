<?php
if (
    isset($_POST['current_time'])
    && isset($_POST['periods'])
    && isset($_POST['subject'])
    && isset($_POST['tb_teacher_id'])
    && isset($_POST['absent'])
    && isset($_POST['class'])
    && isset($_POST['absence_reason'])
) {
    // ไฟล์เชื่อมต่อฐานข้อมูล
    require_once 'connection.php';

    // SQL insert
    $stmt = $conn->prepare("INSERT INTO tb_users_logs
    (`current_time`,
    `periods`,
    `subject`,
    `tb_teacher_id`,
    `class`,
    `absence_reason`,
    `absent`)
    VALUES
    (:current_time,
    :periods,
    :subject,
    :tb_teacher_id,
    :class,
    :absence_reason,
    :absent)");

    // Bind parameter values
    $stmt->bindParam(':current_time', $_POST['current_time'], PDO::PARAM_STR);
    $stmt->bindParam(':class', $_POST['class'], PDO::PARAM_STR);
    $stmt->bindParam(':subject', $_POST['subject'], PDO::PARAM_STR);
    $stmt->bindParam(':tb_teacher_id', $_POST['tb_teacher_id'], PDO::PARAM_STR);
    $stmt->bindParam(':periods', $_POST['periods'], PDO::PARAM_STR);

    // Iterate over the absent array
    foreach ($_POST['absent'] as $absentItem) {
        $absence_reason = $_POST['absence_reason'][$absentItem];
        $stmt->bindParam(':absent', $absentItem, PDO::PARAM_STR);
        $stmt->bindParam(':absence_reason', $absence_reason, PDO::PARAM_STR);
        $stmt->execute();
    }

    $tb_teacher_id = $_POST['tb_teacher_id'];
    $tb_teacher_name = $_POST['tb_teacher_name'];

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
        window.location.href = "report.php?tb_teacher_id=' . $tb_teacher_id . '&tb_teacher_name=' . $tb_teacher_name . '";
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
?>