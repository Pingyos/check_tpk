<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบว่ามีผู้ใช้ในฐานข้อมูลหรือไม่
    $query = "SELECT * FROM tb_users WHERE email = :email";
    $statement = $conn->prepare($query);
    $statement->bindParam(':email', $email);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($password === $user['password']) {
            $tb_teacher_id = $user['tb_teacher_id'];

            // รหัสผ่านถูกต้อง
            echo '
            <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
            <script>
                swal({
                    title: "เข้าสู่ระบบสำเร็จ!",
                    text: "กรุณารอสักครู่",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                }, function(){
                    window.location.href = "index.php?tb_teacher_id=' . $tb_teacher_id . '";
                });
            </script>';
            exit;
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo '
            <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
            <script>
                swal("รหัสผ่านไม่ถูกต้อง!", "กรุณาติดต่อหน้าหน้าที่", "error");
            </script>';
        }
    } else {
        // ไม่พบผู้ใช้
        echo '
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <script>
            swal("ไม่พบผู้ใช้!",
             "กรุณาติดต่อหน้าหน้าที่", 
             "error");
        </script>';
    }
}
