<?php
require_once 'TCPDF/tcpdf.php'; // Adjust the path to the TCPDF library

function getRoomLabel($roomNumber)
{
    $class = ($roomNumber - 1) % 3 + 1;
    $year = floor(($roomNumber - 1) / 3) + 1;
    return 'ม.' . $year . '/' . $class;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ... (existing code)

    // Set PDF Headers
    header("Content-type: application/pdf");
    header("Content-Disposition: attachment; filename=data.pdf");

    // Create new TCPDF object
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Data PDF');
    $pdf->SetSubject('Data Export');
    $pdf->SetKeywords('Data, Export, PDF');

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set default font
    $pdf->SetFont('helvetica', '', 10);

    // Add a page
    $pdf->AddPage();

    // Add your content here

    // Write the header row
    $headerRow = ['รหัสนักเรียน', 'ชื่อ-สกุล', 'สาเหตุ', 'ระดับชั้น', 'วิชา', 'ครูผู้สอน', 'คาบเรียน/วันที่'];
    $pdf->Write(0, implode("\t", $headerRow), '', 0, 'L', true, 0, false, false, 0);

    // Write the data rows
    foreach ($students as $student) {
        $rowData = [
            $student['absent'],
            $student['tb_student_tname'] . ' ' . $student['tb_student_name'] . ' ' . $student['tb_student_sname'],
            $student['cause'],
            getRoomLabel($student['rooms']),
            $student['courses'] . ' ' . $student['course_name'],
            $student['name'] . ' ' . $student['surname'],
            $student['period'] . ' ' . $student['time'],
            // ...
        ];
        $pdf->Write(0, implode("\t", $rowData), '', 0, 'L', true, 0, false, false, 0);
    }

    // Close and output the PDF
    $pdf->Output('data.pdf', 'I');
}
