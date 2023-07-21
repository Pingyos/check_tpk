<?php
function getRoomLabel($roomNumber)
{
    $class = ($roomNumber - 1) % 3 + 1;
    $year = floor(($roomNumber - 1) / 3) + 1;
    return 'ม.' . $year . '/' . $class;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ติดต่อกับฐานข้อมูลเหมือนในหน้าฟอร์มหลักเพื่อเลือกตัวกรองข้อมูล
    require_once 'connect.php';

    // ตรวจสอบค่าที่เลือกจากฟอร์ม
    $selectedCourse = isset($_POST['course']) ? $_POST['course'] : '';
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
    $studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

    // ดึงข้อมูลนักเรียนจากฐานข้อมูล
    $sql = "SELECT c.*, s.tb_student_tname, s.tb_student_name, s.tb_student_sname FROM ck_checking c 
            JOIN ck_students s ON c.absent = s.tb_student_code
            WHERE 1=1";

    if ($selectedCourse) {
        $sql .= " AND c.courses = :courseCode";
    }

    if ($startDate && $endDate) {
        $sql .= " AND DATE(c.time) BETWEEN :startDate AND :endDate";
    }

    if ($studentCode) {
        $sql .= " AND c.absent = :studentCode";
    }

    $stmt = $conn->prepare($sql);

    if ($selectedCourse) {
        $stmt->bindParam(':courseCode', $selectedCourse);
    }

    if ($startDate && $endDate) {
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
    }

    if ($studentCode) {
        $stmt->bindParam(':studentCode', $studentCode);
    }

    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ปิดการเชื่อมต่อฐานข้อมูล
    $conn = null;

    // กำหนดหัวข้อสำหรับ CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');

    // เปิดสตรีมข้อมูลออก
    $output = fopen('php://output', 'w');

    // เขียนหัวข้อ CSV
    fputcsv($output, ['รหัสนักเรียน', 'ชื่อ-สกุล', 'สาเหตุ', 'ระดับชั้น', 'วิชา', 'ครูผู้สอน', 'คาบเรียน/วันที่']);

    // เขียนข้อมูลแถว
    foreach ($students as $student) {
        $rowData = [
            $student['absent'],
            $student['tb_student_tname'] . ' ' . $student['tb_student_name'].' '. $student['tb_student_sname'],
            $student['cause'],
            getRoomLabel($student['rooms']),
            $student['courses'] . ' ' . $student['course_name'],
            $student['name'] . ' ' . $student['surname'],
            $student['period'] . ' ' . $student['time'],
            // ...
        ];

        fputcsv($output, $rowData);
    }

    // ปิดสตรีมข้อมูลออก
    fclose($output);
}
