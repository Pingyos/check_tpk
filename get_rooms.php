<?php
require_once 'connect.php';

if (isset($_GET['course'])) {
    $selectedCourse = $_GET['course'];

    $sql = "SELECT tb_rooms.tb_room_id, tb_rooms.tb_room_name
    FROM tb_reg_courses
    INNER JOIN tb_rooms ON tb_reg_courses.rooms = tb_rooms.tb_room_id
    WHERE tb_reg_courses.courses = :selectedCourse";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':selectedCourse', $selectedCourse);
    $stmt->execute();

    $rooms = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roomId = $row['tb_room_id'];
        $roomName = $row['tb_room_name'];
        $rooms[] = array('id' => $roomId, 'name' => $roomName);
    }

    header('Content-Type: application/json');
    echo json_encode($rooms);
}
