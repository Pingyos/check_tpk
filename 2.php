<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ติดต่อกับฐานข้อมูลเหมือนในหน้าฟอร์มหลักเพื่อเลือกตัวกรองข้อมูล
    require_once 'connect.php';

    // ตรวจสอบค่าที่เลือกจากฟอร์ม
    $selectedCourse = isset($_POST['course']) ? $_POST['course'] : '';
    $startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
    $endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
    $studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

    // ดึงข้อมูลนักเรียนจากฐานข้อมูล
    $sql = "SELECT c.*, s.tb_student_name, s.tb_student_sname FROM ck_checking c 
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

    // แสดงตารางข้อมูล
    if (count($students) > 0) {
        echo '<table>';
        echo '<tr><th>รหัสนักเรียน</th><th>ชื่อ-สกุล</th><th>สาเหตุ</th><th>ระดับชั้น</th><th>วิชา</th><th>คาบเรียน/วันที่</th></tr>';

        foreach ($students as $student) {
            echo '<tr>';
            echo '<td>' . $student['absent'] . '</td>';
            echo '<td>' . $student['tb_student_name'] . ' ' . $student['tb_student_sname'] . '</td>';
            echo '<td>' . $student['cause'] . '</td>';
            echo '<td>' . $student['rooms'] . '</td>';
            echo '<td>' . $student['courses'] . ' ' . $student['course_name'] . '</td>';
            echo '<td>' . $student['period'] . ' ' . $student['time'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo 'ไม่พบข้อมูลนักเรียนที่ตรงกับเงื่อนไข';
    }
}
