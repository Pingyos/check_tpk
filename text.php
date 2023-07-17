<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// ตั้งค่าการอ่านไฟล์ Excel
$inputFileName = 'path/to/your/file.xlsx';
$spreadsheet = IOFactory::load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();

// ดึงข้อมูลจากตารางและนำเข้าสู่ฐานข้อมูล
foreach ($worksheet->getRowIterator() as $row) {
    $rowData = [];
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    foreach ($cellIterator as $cell) {
        $rowData[] = $cell->getValue();
    }

    // นำเข้าข้อมูลลงในฐานข้อมูล
    // โค้ดนี้ต้องแก้ไขตามโครงสร้างของฐานข้อมูลและตารางที่ต้องการ
    $dataToInsert = [
        'column1' => $rowData[0],
        'column2' => $rowData[1],
        // ...
    ];

    // นำเข้าข้อมูลลงในฐานข้อมูล
    // ตัวอย่างเช่นใช้ PDO สำหรับเชื่อมต่อฐานข้อมูล MySQL
    $pdo = new PDO('mysql:host=localhost;dbname=your_database', 'username', 'password');
    $stmt = $pdo->prepare("INSERT INTO your_table (column1, column2) VALUES (:column1, :column2)");

    // ทำการผูกค่าและส่งคำสั่ง SQL
    $stmt->execute($dataToInsert);
}
?>
