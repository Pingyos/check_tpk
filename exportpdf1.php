<?php
require('fpdf186/fpdf.php');

$selectedCourse = isset($_GET['course']) ? $_GET['course'] : '';
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
$studentCode = isset($_GET['studentCode']) ? $_GET['studentCode'] : '';
$cause = isset($_GET['cause']) ? $_GET['cause'] : 'ขาดเรียน';

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

if ($cause) {
    $sql .= " AND c.cause = :cause";
}

// คริวรีข้อมูลด้วยคำสั่ง SQL
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

if ($cause) {
    $stmt->bindParam(':cause', $cause);
}

$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
// สร้าง PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->AddFont('THSarabunNewBold', '', 'THSarabunNewBold.php');
$pdf->SetFont('THSarabunNew', '', '12');

if (count($students) > 0) {
    $pdf->SetFont('THSarabunNewBold', '', '18');
    $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'รายงานการขาดเรียน'), 0, 1, 'C');
    $pdf->SetFont('THSarabunNewBold', '', '16');
    require_once 'connect.php';
    if (isset($_GET['studentCode'])) {
        $studentCode = $_GET['studentCode'];
        $sqlStudent = "SELECT tb_student_tname, tb_student_name, tb_student_sname FROM ck_students WHERE tb_student_code = :studentCode";
        $stmtStudent = $conn->prepare($sqlStudent);
        $stmtStudent->bindParam(':studentCode', $studentCode);
        $stmtStudent->execute();
        $studentData = $stmtStudent->fetch(PDO::FETCH_ASSOC);
        if ($studentData) {
            $studentName = $studentData['tb_student_tname'] . ' ' . $studentData['tb_student_name'] . ' ' . $studentData['tb_student_sname'];
            $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'ชื่อนักเรียน: ' . $studentName . ' ' . 'รหัสนักเรียน: ' . $studentCode), 0, 1, 'C');
        }
    }
    require_once 'connect.php';
    if (isset($_GET['course'])) {
        $selectedCourse = $_GET['course'];
        $sqlCourse = "SELECT tb_course_name FROM ck_courses WHERE tb_course_code = :selectedCourse";
        $stmtCourse = $conn->prepare($sqlCourse);
        $stmtCourse->bindParam(':selectedCourse', $selectedCourse);
        $stmtCourse->execute();
        $courseData = $stmtCourse->fetch(PDO::FETCH_ASSOC);

        if ($courseData) {
            $tb_course_name = $courseData['tb_course_name'];
            $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'รายวิชา: ' . $tb_course_name), 0, 1, 'C');
        }
    }

    function formatDateThai($date)
    {
        $dateTime = new DateTime($date);
        $thaiMonths = array(
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม',
            'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน',
            'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        );
        $formattedDateThai = $dateTime->format('d') . ' ' . $thaiMonths[$dateTime->format('m') - 1] . ' ' . ($dateTime->format('Y') + 543);
        return $formattedDateThai;
    }
    $startDateFormattedThai = formatDateThai($startDate);
    $endDateFormattedThai = formatDateThai($endDate);

    $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'ระหว่างวันที่: ' . $startDateFormattedThai . '  ถึงวันที่: ' . $endDateFormattedThai), 0, 1, 'C');
    $pdf->Cell(0, 7, iconv('utf-8', 'cp874', ''), 0, 1, 'C');

    $pdf->SetFont('THSarabunNewBold', '', 12);
    $pdf->Cell(20, 10, iconv('utf-8', 'cp874', 'ลำดับ'), 1, 0, 'C');
    $pdf->Cell(30, 10, iconv('utf-8', 'cp874', 'รหัสนักเรียน'), 1, 0, 'C');
    $pdf->Cell(80, 10, iconv('utf-8', 'cp874', 'ชื่อ-สกุล'), 1, 0, 'C');
    $pdf->Cell(25, 10, iconv('utf-8', 'cp874', 'ระดับชั้น'), 1, 0, 'C');
    $pdf->Cell(30, 10, iconv('utf-8', 'cp874', 'จำนวนคาบ'), 1, 1, 'C');

    function compareStudents($a, $b)
    {

        $startDiff = strtotime($b['time']) - strtotime($a['time']);
        if ($startDiff !== 0) {
            return $startDiff;
        }
        return $a['rooms'] - $b['rooms'];
    }
    usort($students, 'compareStudents');
    $counter = 1;
    foreach ($students as $student) {
        $roomNumber = is_numeric($student['rooms']) ? $student['rooms'] : 0;

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
        $pdf->SetFont('THSarabunNew', '', 12);
        $pdf->Cell(20, 10, iconv('utf-8', 'cp874', $counter), 1, 0, 'C');
        $pdf->Cell(30, 10, iconv('utf-8', 'cp874', $student['absent']), 1, 0, 'C');
        $pdf->Cell(80, 10, iconv('utf-8', 'cp874', $student['tb_student_tname'] . ' ' . $student['tb_student_name'] . ' ' . $student['tb_student_sname']), 1, 0, 'L');
        $pdf->Cell(25, 10, iconv('utf-8', 'cp874', $roomDisplay), 1, 0, 'C');
        $periodNumbers = explode(',', $student['period']);
        $numberCount = count($periodNumbers);
        $totalNumberCount += $numberCount;
        $pdf->Cell(30, 10, iconv('utf-8', 'cp874', $numberCount), 1, 1, 'C');
        $counter++;
    }
} else {
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ไม่มีข้อมูลนักเรียนที่ขาด'), 0, 1, 'C');
}

$pdf->SetFont('THSarabunNewBold', '', 16);

$pdf->Cell(155, 10, iconv('utf-8', 'cp874', 'รวม' . ' '), 1, 0, 'R');
$pdf->Cell(30, 10, iconv('utf-8', 'cp874', '' . ' ' . $totalNumberCount), 1, 0, 'C');


$pdf->SetFont('THSarabunNew', '', '14');
$pdf->Cell(0, 30, iconv('utf-8', 'cp874', ''), 0, 1, 'C');
$pdf->Cell(65, 7, iconv('utf-8', 'cp874', 'ลงชื่อ .................................................'), 0, 0, 'C');
$pdf->Cell(65, 7, iconv('utf-8', 'cp874', 'ลงชื่อ .................................................'), 0, 0, 'C');
$pdf->Cell(65, 7, iconv('utf-8', 'cp874', 'ลงชื่อ .................................................'), 0, 1, 'C');

require_once 'connect.php';
$id = 2001;
$sql = "SELECT * FROM ck_users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$courseData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($courseData) {
    $name = $courseData['name'];
    $pdf->Cell(65, 7, iconv('utf-8', 'cp874', '(' . $name . ')'), 0, 0, 'C');
}
$id = 2002;
$sql = "SELECT * FROM ck_users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$courseData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($courseData) {
    $name = $courseData['name'];
    $pdf->Cell(65, 7, iconv('utf-8', 'cp874', '(' . $name . ')'), 0, 0, 'C');
}
$id = 2003;
$sql = "SELECT * FROM ck_users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$courseData = $stmt->fetch(PDO::FETCH_ASSOC);

if ($courseData) {
    $name = $courseData['name'];
    $pdf->Cell(65, 7, iconv('utf-8', 'cp874', '(' . $name . ')'), 0, 1, 'C');
}
$pdf->Cell(65, 7, iconv('utf-8', 'cp874', 'ผู้ช่วยรองผู้อำนวยการฝ่ายวิชาการ'), 0, 0, 'C');
$pdf->Cell(65, 7, iconv('utf-8', 'cp874', 'รองผู้อำนวยการโรงเรียนถ้ำปินวิทยาคม'), 0, 0, 'C');
$pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'ผู้อำนวยการโรงเรียนถ้ำปินวิทยาคม'), 0, 1, 'C');

ob_end_clean();
$filename = "report_" . date('Y-m-d') . ".pdf";
header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"$filename\"");
$pdf->Output();
