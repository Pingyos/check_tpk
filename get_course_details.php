<?php
require_once 'connection.php';

if (isset($_GET['course_code'])) {
    $courseCode = $_GET['course_code'];

    // ดึงข้อมูลจากตาราง tb_students ที่มี tb_course_code ตรงกับ course_code ที่ส่งมา
    $sql = "SELECT * FROM tb_students WHERE tb_course_code = :course_code";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':course_code', $courseCode, PDO::PARAM_STR);
    $stmt->execute();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($stmt->rowCount() > 0) {
        // สร้างตาราง HTML สำหรับแสดงข้อมูล
        $tableHTML = '<form method="post" action="process.php">'; // เพิ่ม form เพื่อส่งข้อมูลไปยัง process.php
        $tableHTML .= '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>ขาดเรียน</th>
                            </tr>
                        </thead>
                        <tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $studentId = $row["tb_student_id"];
            $studentName = $row["tb_student_name"];
            $studentCode = $row["tb_student_code"];

            $tableHTML .= '<tr>
                    <td>' . $studentId . '</td>
                    <td>' . $studentName . '</td>
                    <td><input type="checkbox" name="absent[]" value="' . $studentCode . '"></td>
                </tr>';
        }

        $tableHTML .= '</tbody></table>';
        $tableHTML .= '</form>';

        echo $tableHTML;
    } else {
        echo 'ไม่พบข้อมูลนักเรียนในวิชานี้';
    }
}
