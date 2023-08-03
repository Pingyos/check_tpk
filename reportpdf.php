<?php
require('fpdf186/fpdf.php');

$selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
$studentCode = isset($_GET['studentCode']) ? $_GET['studentCode'] : '';
$cause = isset($_GET['cause']) ? $_GET['cause'] : '';
$teacherId = isset($_GET['teacherId']) ? $_GET['teacherId'] : '';


require_once 'connect.php';

$sql = "SELECT c.*, s.tb_student_tname, s.tb_student_name, s.tb_student_sname FROM ck_checking c 
JOIN ck_students s ON c.absent = s.tb_student_code
WHERE 1=1";

// เพิ่มเงื่อนไขใน SQL ให้กรองตาม teacher_id
if ($teacherId) {
    $sql .= " AND c.teacher_id = :teacherId";
}

if ($selectedCourse) {
    $sql .= " AND c.courses = :courseCode";
}

if ($startDate && $endDate) {
    $sql .= " AND DATE(c.time) BETWEEN :startDate AND :endDate";
}

if ($studentCode) {
    $sql .= " AND c.absent = :studentCode";
}

if ($cause) {
    $sql .= " AND c.cause = :cause";
}

// คริวรีข้อมูลด้วยคำสั่ง SQL
$stmt = $conn->prepare($sql);

// ตรวจสอบว่ามีค่า teacher_id ถูกส่งมาหรือไม่ ถ้ามีให้ bind parameter ให้กับตัวแปร $teacherId
if ($teacherId) {
    $stmt->bindParam(':teacherId', $teacherId);
}

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

if ($cause) {
    $stmt->bindParam(':cause', $cause);
}

$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);





// สร้าง PDF
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->AddFont('THSarabunNewBold', '', 'THSarabunNewBold.php');
$pdf->SetFont('THSarabunNew', '', '12');

// Start output buffering to prevent any output before PDF generation
ob_start();

// สร้างตารางข้อมูลจาก $students
if (count($students) > 0) {
    $pdf->SetFont('THSarabunNewBold', '', '18');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'รายงานการขาดเรียน'), 0, 1, 'C');
    require_once 'connect.php';
    if (isset($_GET['studentCode'])) {
        // รับค่า studentCode ที่ส่งมา
        $studentCode = $_GET['studentCode'];

        // เตรียมคำสั่ง SQL เพื่อค้นหาชื่อนักเรียนจากตาราง ck_students
        $sqlStudent = "SELECT tb_student_tname, tb_student_name, tb_student_sname FROM ck_students WHERE tb_student_code = :studentCode";
        $stmtStudent = $conn->prepare($sqlStudent);
        $stmtStudent->bindParam(':studentCode', $studentCode);
        $stmtStudent->execute();
        $studentData = $stmtStudent->fetch(PDO::FETCH_ASSOC);

        // ถ้าค้นพบข้อมูลนักเรียนที่มี tb_student_code ตรงกับ studentCode ที่ส่งมาก็จะแสดงชื่อนักเรียนนั้นใน PDF
        if ($studentData) {
            $studentName = $studentData['tb_student_tname'] . ' ' . $studentData['tb_student_name'] . ' ' . $studentData['tb_student_sname'];
            $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ชื่อนักเรียน: ' . $studentName . ' ' . 'รหัสนักเรียน: ' . $studentCode), 0, 1, 'C');
        }
    }
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ระหว่างวันที่: ' . $startDate . '  ถึงวันที่: ' . $endDate), 0, 1, 'C');
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', ''), 0, 1, 'C');

    // กำหนดขนาดฟอนต์ของตารางเป็น 12
    $pdf->SetFont('THSarabunNew', '', 12);

    // กำหนดความกว้างของคอลัมน์ในตาราง
    $pdf->Cell(20, 10, iconv('utf-8', 'cp874', 'วันที่'), 1, 0, 'C');
    $pdf->Cell(60, 10, iconv('utf-8', 'cp874', 'ชื่อ-สกุล'), 1, 0, 'C');
    $pdf->Cell(15, 10, iconv('utf-8', 'cp874', 'ระดับชั้น'), 1, 0, 'C');
    $pdf->Cell(60, 10, iconv('utf-8', 'cp874', 'วิชา'), 1, 0, 'C');
    $pdf->Cell(90, 10, iconv('utf-8', 'cp874', 'สาเหตุ'), 1, 0, 'C');
    $pdf->Cell(30, 10, iconv('utf-8', 'cp874', 'ตาบเรียน'), 1, 1, 'C');

    foreach ($students as $student) {
        $roomNumber = is_numeric($student['rooms']) ? $student['rooms'] : 0;

        // แทนค่าตามเงื่อนไข
        switch ($roomNumber) {
            case 1:
                $roomDisplay = 'ม.1/1';
                break;
            case 2:
                $roomDisplay = 'ม.1/2';
                break;
            case 3:
                $roomDisplay = 'ม.1/3';
                break;
            case 4:
                $roomDisplay = 'ม.2/1';
                break;
            case 5:
                $roomDisplay = 'ม.2/2';
                break;
            case 6:
                $roomDisplay = 'ม.2/3';
                break;
            case 7:
                $roomDisplay = 'ม.3/1';
                break;
            case 8:
                $roomDisplay = 'ม.3/2';
                break;
            case 9:
                $roomDisplay = 'ม.3/3';
                break;
            case 10:
                $roomDisplay = 'ม.4/1';
                break;
            case 11:
                $roomDisplay = 'ม.4/2';
                break;
            case 12:
                $roomDisplay = 'ม.4/3';
                break;
            case 13:
                $roomDisplay = 'ม.5/1';
                break;
            case 14:
                $roomDisplay = 'ม.5/2';
                break;
            case 15:
                $roomDisplay = 'ม.5/3';
                break;
            case 16:
                $roomDisplay = 'ม.6/1';
                break;
            case 17:
                $roomDisplay = 'ม.6/2';
                break;
            case 18:
                $roomDisplay = 'ม.6/3';
                break;
            default:
                $roomDisplay = 'ไม่ทราบ';
                break;
        }

        $pdf->Cell(20, 10, iconv('utf-8', 'cp874', $student['time']), 1, 0, 'L');
        $pdf->Cell(60, 10, iconv('utf-8', 'cp874', $student['tb_student_tname'] . ' ' . $student['tb_student_name'] . ' ' . $student['tb_student_sname']), 1, 0, 'L');
        $pdf->Cell(15, 10, iconv('utf-8', 'cp874', $roomDisplay), 1, 0, 'L');
        $pdf->Cell(60, 10, iconv('utf-8', 'cp874', $student['courses'] . ' - ' . $student['course_name']), 1, 0, 'L');
        $pdf->Cell(90, 10, iconv('utf-8', 'cp874', $student['cause'] . '  ' . ($student['custom_cause'] ? '* ' . $student['custom_cause'] : '')), 1, 0, 'L');
        $pdf->Cell(30, 10, iconv('utf-8', 'cp874', $student['period']), 1, 1, 'L');
    }
} else {
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ไม่มีข้อมูลนักเรียนที่ขาด'), 0, 1, 'C');
}

// Clear the output buffer
ob_end_clean();
$filename = "report_" . date('Y-m-d') . ".pdf";

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"$filename\"");
$pdf->Output();
