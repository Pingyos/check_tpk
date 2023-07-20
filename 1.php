<?php
// Existing PHP code for data retrieval and table display

// Check if the "Export" button is clicked
if (isset($_POST['export_csv'])) {
    // Retrieve the selected course, start date, end date, and student code for export
    $exportCourse = $_POST['export_course'];
    $exportStartDate = $_POST['export_startDate'];
    $exportEndDate = $_POST['export_endDate'];
    $exportStudentCode = $_POST['export_studentCode'];

    // Perform the data retrieval again, this time for the export
    // ...
    // (Put your existing data retrieval code here)

    // After fetching the data into the $students array

    // Prepare the CSV content
    $csvContent = "รหัสนักเรียน,ชื่อ-สกุล,สาเหตุ,ระดับชั้น,วิชา,ตาบเรียน/วันที่\n";
    foreach ($students as $student) {
        $csvContent .= "{$student['absent']},{$student['tb_student_name']} {$student['tb_student_sname']},{$student['cause']},";
        $csvContent .= "ม." . (floor(($student['rooms'] - 1) / 3) + 1) . '/' . (($student['rooms'] - 1) % 3 + 1) . ",";
        $csvContent .= "{$student['courses']} - {$student['course_name']},{$student['period']} / {$student['time']}\n";
    }

    // Set the appropriate headers to trigger the download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data_export.csv');

    // Output the CSV content
    echo "\xEF\xBB\xBF"; // BOM (Byte Order Mark) to handle UTF-8 encoding in Excel
    echo $csvContent;
    exit;
}
