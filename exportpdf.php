<?php
require('fpdf186/fpdf.php');

// รับค่าที่ส่งมาจากหน้า export_date.php
$selectedCourse = isset($_POST['course']) ? $_POST['course'] : '';
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : date('Y-m-d');
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : date('Y-m-d');
$studentCode = isset($_POST['studentCode']) ? $_POST['studentCode'] : '';

// เชื่อมต่อฐานข้อมูล
require_once 'connect.php';

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

// สร้าง PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->AddFont('thsarabunNew', '', 'thsarabunNew.php');
$pdf->SetFont('thsarabunNew', '', '16');

// Start output buffering to prevent any output before PDF generation
ob_start();

// สร้างตารางข้อมูลจาก $students
if (count($students) > 0) {
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'รายงานการขาดเรียน'), 0, 1, 'C');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'วิชา: ' . $selectedCourse), 0, 1, 'C');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'วันที่เริ่มต้น: ' . $startDate . '  วันที่สิ้นสุด: ' . $endDate), 0, 1, 'C');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'รหัสนักเรียน: ' . $studentCode), 0, 1, 'C');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', ''), 0, 1, 'C');

    $pdf->SetFont('thsarabunNew', '', '14');
    $pdf->Cell(25, 10, iconv('utf-8', 'cp874', 'รหัสนักเรียน'), 1, 0, 'C');
    $pdf->Cell(45, 10, iconv('utf-8', 'cp874', 'ชื่อ-สกุล'), 1, 0, 'C');
    $pdf->Cell(60, 10, iconv('utf-8', 'cp874', 'สาเหตุ'), 1, 0, 'C');
    $pdf->Cell(30, 10, iconv('utf-8', 'cp874', 'ระดับชั้น'), 1, 0, 'C');
    $pdf->Cell(40, 10, iconv('utf-8', 'cp874', 'วิชา'), 1, 0, 'C');
    $pdf->Cell(40, 10, iconv('utf-8', 'cp874', 'ตาบเรียน/วันที่'), 1, 1, 'C');

    foreach ($students as $student) {
        // Check if $student['rooms'] is numeric, otherwise use a default value of 0
        $roomNumber = is_numeric($student['rooms']) ? $student['rooms'] : 0;
        $pdf->Cell(25, 10, iconv('utf-8', 'cp874', $student['absent']), 1, 0, 'C');
        $pdf->Cell(45, 10, iconv('utf-8', 'cp874', $student['tb_student_tname'] . ' ' . $student['tb_student_name'] . ' ' . $student['tb_student_sname']), 1, 0, 'L');
        $pdf->Cell(60, 10, iconv('utf-8', 'cp874', $student['cause'] . '  ' . ($student['custom_cause'] ? '* ' . $student['custom_cause'] : '')), 1, 0, 'L');
        $pdf->Cell(30, 10, iconv('utf-8', 'cp874', 'ม.' . floor(($roomNumber - 1) / 3) + 1 . '/' . (($roomNumber - 1) % 3 + 1)), 1, 0, 'C');
        $pdf->Cell(40, 10, iconv('utf-8', 'cp874', $student['courses'] . ' - ' . $student['course_name']), 1, 0, 'L');
        $pdf->Cell(40, 10, iconv('utf-8', 'cp874', $student['period'] . ' / ' . $student['time']), 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ไม่มีข้อมูลนักเรียนที่ขาด'), 0, 1, 'C');
}

// Clear the output buffer
ob_end_clean();

$pdf->Output();