<?php
if (
    isset($_POST['time'])
    && isset($_POST['period'])
    && isset($_POST['courses'])
    && isset($_POST['course_name'])
    && isset($_POST['rooms'])
    && isset($_POST['teacher_id'])
    && isset($_POST['name'])
    && isset($_POST['surname'])
    && isset($_POST['absent'])
    && isset($_POST['cause'])
) {
    require_once 'connect.php'; // เชื่อมต่อฐานข้อมูล

    $time = $_POST['time'];
    $period = $_POST['period'];
    $courses = $_POST['courses'];
    $course_name = $_POST['course_name'];
    $rooms = $_POST['rooms'];
    $teacher_id = $_POST['teacher_id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $absent = $_POST['absent'];
    $cause = $_POST['cause'];

    if (is_array($absent) && is_array($cause)) {
        $numRows = count($absent);

        for ($i = 0; $i < $numRows; $i++) {
            $currentAbsent = $absent[$i];
            $currentCause = $cause[$i];

            $stmt = $conn->prepare("INSERT INTO ck_checking (`time`, `period`, `courses`, `course_name`, `rooms`, `teacher_id`, `name`, `surname`, `absent`, `cause`)
                                    VALUES (:time, :period, :courses, :course_name, :rooms, :teacher_id, :name, :surname, :absent, :cause)");

            $stmt->bindParam(':time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':period', $period, PDO::PARAM_STR);
            $stmt->bindParam(':courses', $courses, PDO::PARAM_STR);
            $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
            $stmt->bindParam(':rooms', $rooms, PDO::PARAM_INT);
            $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->bindParam(':absent', $currentAbsent, PDO::PARAM_STR);
            $stmt->bindParam(':cause', $currentCause, PDO::PARAM_STR);

            $stmt->execute();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO ck_checking (`time`, `period`, `courses`, `course_name`, `rooms`, `teacher_id`, `name`, `surname`, `absent`, `cause`)
                                VALUES (:time, :period, :courses, :course_name, :rooms, :teacher_id, :name, :surname, :absent, :cause)");

        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
        $stmt->bindParam(':period', $period, PDO::PARAM_STR);
        $stmt->bindParam(':courses', $courses, PDO::PARAM_STR);
        $stmt->bindParam(':course_name', $course_name, PDO::PARAM_STR);
        $stmt->bindParam(':rooms', $rooms, PDO::PARAM_INT);
        $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
        $stmt->bindParam(':absent', $absent, PDO::PARAM_STR);
        $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);

        $stmt->execute();
    }

    if ($stmt->rowCount() > 0) {
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
