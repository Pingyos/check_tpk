<?php
require_once 'connect.php';

// ตรวจสอบว่ามีการเลือกวิชาที่ส่งมา
if (isset($_GET['course'])) {
    $selectedCourse = $_GET['course'];

    // เขียนคำสั่ง SQL เพื่อดึงข้อมูลห้องเรียนที่เกี่ยวข้องกับวิชาที่เลือก
    $sql = "SELECT DISTINCT tb_reg_courses.rooms, tb_rooms.tb_room_id
    FROM tb_reg_courses
    INNER JOIN tb_rooms ON tb_reg_courses.rooms = tb_rooms.tb_room_id
    WHERE tb_reg_courses.courses = :selectedCourse";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':selectedCourse', $selectedCourse);
    $stmt->execute();

    $rooms = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rooms[] = $row['tb_room_id'];
    }

    // ส่งข้อมูลห้องเรียนในรูปแบบ JSON
    header('Content-Type: application/json');
    echo json_encode($rooms);
}
