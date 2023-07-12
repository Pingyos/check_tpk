<?php
session_start(); // เพิ่มคำสั่ง session_start() เพื่อเรียกใช้ session

if (isset($_POST['email']) && isset($_POST['password'])) {
    echo '
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    require_once 'connection.php';
    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password'];

    //check email  & password
    $stmt = $conn->prepare("SELECT tb_teacher_id, tb_teacher_name FROM tb_users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    //กรอก email & password ถูกต้อง
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['tb_teacher_id'] = $row['tb_teacher_id'];
        $_SESSION['tb_teacher_name'] = $row['tb_teacher_name'];
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
                window.location.href = "index.php?tb_teacher_id=' .$_SESSION['tb_teacher_id'] . '&tb_teacher_name=' . $_SESSION['tb_teacher_name'] . '";
            });
        </script>';
        exit();
    } else {
        echo '<script>
                       setTimeout(function() {
                        swal({
                            title: "เกิดข้อผิดพลาด",
                             text: "email หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                            type: "warning"
                        }, function() {
                            window.location = "login.php"; //หน้าที่ต้องการให้กระโดดไป
                        });
                      }, 1000);
                  </script>';
        $conn = null; //close connect db
    } //else
} //isset 
    //devbanban.com
