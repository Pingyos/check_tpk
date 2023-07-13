<?php
if (
    isset($_POST['course_code'])
    && isset($_POST['course_name'])
    && isset($_POST['degree'])
    && isset($_POST['teacher_id'])
    && isset($_POST['name'])
    && isset($_POST['surname'])
) {
    require_once 'connect.php';

    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $degree = $_POST['degree'];
    $teacherId = $_POST['teacher_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    $stmt = $conn->prepare("INSERT INTO tb_reg_courses (`course_code`, `course_name`, `degree`, `teacher_id`, `name`, `surname`)
                            VALUES (:course_code, :course_name, :degree, :teacher_id, :name, :surname)");
    $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
    $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
    $stmt->bindParam(':degree', $degree, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacherId, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo '<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>';
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';
        echo '<script>
        swal({
            title: "บันทึกข้อมูลสำเร็จ", 
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
        swal({
            title: "เกิดข้อผิดพลาดในการบันทึกข้อมูล",
            type: "error"
        }, function() {
            window.location = "index.php";
        });
        </script>';
    }
}
