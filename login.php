<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-8"> <br>
                <form action="" method="post">
                    <div class="mb-2">
                        <div class="col-sm-9">
                            <input type="text" name="email" class="form-control" required minlength="3" placeholder="email">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control" required minlength="3" placeholder="password">
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-sm-9 mb-3">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>


<?php

//print_r($_POST); //ตรวจสอบมี input อะไรบ้าง และส่งอะไรมาบ้าง 
//ถ้ามีค่าส่งมาจากฟอร์ม
if (isset($_POST['email']) && isset($_POST['password'])) {
    // sweet alert 
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    //ไฟล์เชื่อมต่อฐานข้อมูล
    require_once 'connect.php';
    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $email = $_POST['email'];
    $password = $_POST['password']; //เก็บรหัสผ่านในรูปแบบ sha1 

    //check email  & password
    $stmt = $conn->prepare("SELECT id, name, surname FROM tb_users WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    //กรอก email & password ถูกต้อง
    if ($stmt->rowCount() == 1) {
        //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //สร้างตัวแปร session
        $_SESSION['id'] = $row['id'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['surname'] = $row['surname'];
        echo '<script>
                swal({
                    title: "เข้าสู่ระบบสำเร็จ!",
                    text: "กรุณารอสักครู่",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false
                }, function(){
                    window.location.href = "index.php";
                });
            </script>';
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
?>