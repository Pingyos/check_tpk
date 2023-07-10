<?php
require_once 'connection.php';

if (isset($_POST['subject'])) {
    $selectedSubject = $_POST['subject'];

    // แยกค่า tb_course_code และ tb_teacher_id จากวิชาที่เลือก
    $explodedValues = explode(' - ', $selectedSubject);
    $tb_course_code = $explodedValues[0];

    // ดึงข้อมูลนักเรียนจากตาราง tb_students ที่มี tb_course_code ตรงกับค่าที่ถูกเลือก
    $sql_students = "SELECT tb_student_code, tb_student_name, tb_student_sname FROM tb_students WHERE tb_course_code = :course_code";
    $stmt_students = $conn->prepare($sql_students);
    $stmt_students->bindParam(':course_code', $tb_course_code);
    $stmt_students->execute();

    if ($stmt_students->rowCount() > 0) {
        $output = '<table>';
        $output .= '<thead><tr>
                <th>รหัสนักเรียน</th>
                <th>ชื่อนักเรียน</th>
                <th>ขาดเรียน</th>
            </tr></thead>';
        $output .= '<tbody>';

        while ($row_students = $stmt_students->fetch(PDO::FETCH_ASSOC)) {
            $tb_student_code = $row_students["tb_student_code"];
            $tb_student_name = $row_students["tb_student_name"];
            $tb_student_sname = $row_students["tb_student_sname"];

            $checked = (!empty($_POST['absent']) && in_array($tb_student_code, $_POST['absent'])) ? 'checked' : '';

            $output .= '<tr>';
            $output .= '<td>' . $tb_student_code . '</td>';
            $output .= '<td>' . $tb_student_name . ' ' . $tb_student_sname . '</td>';
            $output .= '<td><input type="checkbox" name="absent[]" value="' . $tb_student_code . '" ' . $checked . '></td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';

        echo $output;
    } else {
        echo 'ไม่พบข้อมูลนักเรียน';
    }
}
