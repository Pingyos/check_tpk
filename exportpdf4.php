<?php
require('fpdf186/fpdf.php');

$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
$rooms = isset($_GET['rooms']) ? $_GET['rooms'] : '';


require_once 'connect.php';

$sql = "SELECT c.*, s.tb_student_tname, s.tb_student_name, s.tb_student_sname FROM ck_checking c 
JOIN ck_students s ON c.absent = s.tb_student_code
WHERE 1=1";

if ($startDate && $endDate) {
    $sql .= " AND DATE(c.time) BETWEEN :startDate AND :endDate";
}

if ($rooms) {
    $sql .= " AND c.rooms = :rooms";
}

// คริวรีข้อมูลด้วยคำสั่ง SQL
$stmt = $conn->prepare($sql);

if ($startDate && $endDate) {
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);
}

if ($rooms) {
    $stmt->bindParam(':rooms', $rooms);
}

$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
$pdf->AddFont('THSarabunNewBold', '', 'THSarabunNewBold.php');
$pdf->SetFont('THSarabunNew', '', '12');

ob_start();

if (count($students) > 0) {
    $pdf->SetFont('THSarabunNewBold', '', '18');
    $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'รายงานการขาดเรียน'), 0, 1, 'C');
    $pdf->SetFont('THSarabunNewBold', '', '16');
    switch ($rooms) {
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
    $pdf->Cell(0, 7, iconv('utf-8', 'cp874', 'ชั้น: ' . $roomDisplay), 0, 1, 'C');
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
    $pdf->Cell(20, 10, iconv('utf-8', 'cp874', 'จำนวนคาบ'), 1, 1, 'C');

    $pdf->SetFont('THSarabunNew', '', 12);
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
        $pdf->Cell(20, 10, iconv('utf-8', 'cp874', $counter), 1, 0, 'C');
        $roomNumber = is_numeric($student['rooms']) ? $student['rooms'] : 0;
        $pdf->Cell(30, 10, iconv('utf-8', 'cp874', $student['absent']), 1, 0, 'C');
        $pdf->Cell(80, 10, iconv('utf-8', 'cp874', $student['tb_student_tname'] . ' ' . $student['tb_student_name'] . ' ' . $student['tb_student_sname']), 1, 0, 'L');
        $periodNumbers = explode(',', $student['period']);
        $numberCount = count($periodNumbers);
        $totalNumberCount += $numberCount;
        $pdf->Cell(20, 10, iconv('utf-8', 'cp874', $numberCount), 1, 1, 'C');
        $counter++;
    }
} else {
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ไม่มีข้อมูลนักเรียนที่ขาด'), 0, 1, 'C');
}
$pdf->SetFont('THSarabunNewBold', '', 16);
$pdf->Cell(130, 10, iconv('utf-8', 'cp874', 'รวม' . ' '), 1, 0, 'R');
$pdf->Cell(20, 10, iconv('utf-8', 'cp874', '' . ' ' . $totalNumberCount), 1, 0, 'C');

$pdf->SetFont('THSarabunNew', '', '14');
$pdf->Cell(0, 10, iconv('utf-8', 'cp874', ''), 0, 1, 'C');
$pdf->Cell(90, 30, iconv('utf-8', 'cp874', 'ลงชื่อรับทราบข้อมูล'), 0, 1, 'C');
$pdf->Cell(90, 7, iconv('utf-8', 'cp874', 'ลงชื่อ .................................................'), 0, 1, 'C');
$pdf->Cell(90, 7, iconv('utf-8', 'cp874', '( .................................................)'), 0, 1, 'C');


$pdf->Cell(90, 7, iconv('utf-8', 'cp874', '( ครูที่ปรึกษา )'), 0, 1, 'C');
// Clear the output buffer
ob_end_clean();
$filename = "report_" . date('Y-m-d') . ".pdf";

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=\"$filename\"");
$pdf->Output();
