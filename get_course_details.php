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
        $tableHTML = '<form method="post" action="process.php">';
        $tableHTML .= '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>รหัสนักเรียน/th>
                                <th>ชื่อ-สกุล</th>
                                <th>ขาดเรียน</th>
                                <th>สาเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $studentId = $row["tb_student_id"];
            $studentName = $row["tb_student_name"];
            $studentSname = $row["tb_student_sname"];
            $studentCode = $row["tb_student_code"];

            $tableHTML .= '<tr>
                <td>' . $studentId . '</td>
                <td>' . $studentName . ' ' . $studentSname . '</td>
                <td><input type="checkbox" name="absent[]" value="' . $studentCode . '"></td>
                <td><input type="text" class="form-control form-control-user" name="absence_reason[' . $studentCode . ']" onblur="checkEmpty(this)"></td>
            </tr>';
        }

        $tableHTML .= '</tbody></table>';
        $tableHTML .= '</form>';

        echo $tableHTML;
    } else {
        echo 'ไม่พบข้อมูลนักเรียนในวิชานี้';
    }
}
?>