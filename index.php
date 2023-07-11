<!DOCTYPE html>
<html lang="en">
<?php require_once 'head.php'; ?>

<body id="page-top">
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php require_once 'topbar.php'; ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800"></h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <form class="user" method="POST">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="current_time" class="form-label">วันที่</label>
                                        <input type="date" class="form-control form-control-user" id="current_time" name="current_time">
                                    </div>
                                    <script>
                                        var currentDateInput = document.getElementById('current_time');
                                        var currentDate = new Date();
                                        var year = currentDate.getFullYear();
                                        var month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
                                        var day = ("0" + currentDate.getDate()).slice(-2);
                                        currentDateInput.value = year + "-" + month + "-" + day;
                                    </script>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="period" class="form-label">คาบเรียน</label>
                                        <div class="body">
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period1', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 256 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M160 64c0-11.8-6.5-22.6-16.9-28.2s-23-5-32.8 1.6l-96 64C-.5 111.2-4.4 131 5.4 145.8s29.7 18.7 44.4 8.9L96 123.8V416H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96 96c17.7 0 32-14.3 32-32s-14.3-32-32-32H160V64z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period2', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M142.9 96c-21.5 0-42.2 8.5-57.4 23.8L54.6 150.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L40.2 74.5C67.5 47.3 104.4 32 142.9 32C223 32 288 97 288 177.1c0 38.5-15.3 75.4-42.5 102.6L109.3 416H288c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9L200.2 234.5c15.2-15.2 23.8-35.9 23.8-57.4c0-44.8-36.3-81.1-81.1-81.1z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period3', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M0 64C0 46.3 14.3 32 32 32H272c13.2 0 25 8.1 29.8 20.4s1.5 26.3-8.2 35.2L162.3 208H184c75.1 0 136 60.9 136 136s-60.9 136-136 136H105.4C63 480 24.2 456 5.3 418.1l-1.9-3.8c-7.9-15.8-1.5-35 14.3-42.9s35-1.5 42.9 14.3l1.9 3.8c8.1 16.3 24.8 26.5 42.9 26.5H184c39.8 0 72-32.2 72-72s-32.2-72-72-72H80c-13.2 0-25-8.1-29.8-20.4s-1.5-26.3 8.2-35.2L189.7 96H32C14.3 96 0 81.7 0 64z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period4', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M189 77.6c7.5-16 .7-35.1-15.3-42.6s-35.1-.7-42.6 15.3L3 322.4c-4.7 9.9-3.9 21.5 1.9 30.8S21 368 32 368H256v80c0 17.7 14.3 32 32 32s32-14.3 32-32V368h32c17.7 0 32-14.3 32-32s-14.3-32-32-32H320V160c0-17.7-14.3-32-32-32s-32 14.3-32 32V304H82.4L189 77.6z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period5', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M32.5 58.3C35.3 43.1 48.5 32 64 32H256c17.7 0 32 14.3 32 32s-14.3 32-32 32H90.7L70.3 208H184c75.1 0 136 60.9 136 136s-60.9 136-136 136H100.5c-39.4 0-75.4-22.3-93-57.5l-4.1-8.2c-7.9-15.8-1.5-35 14.3-42.9s35-1.5 42.9 14.3l4.1 8.2c6.8 13.6 20.6 22.1 35.8 22.1H184c39.8 0 72-32.2 72-72s-32.2-72-72-72H32c-9.5 0-18.5-4.2-24.6-11.5s-8.6-16.9-6.9-26.2l32-176z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period6', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M232.4 84.7c11.4-13.5 9.7-33.7-3.8-45.1s-33.7-9.7-45.1 3.8L38.6 214.7C14.7 242.9 1.1 278.4 .1 315.2c0 1.4-.1 2.9-.1 4.3c0 .2 0 .3 0 .5c0 88.4 71.6 160 160 160s160-71.6 160-160c0-85.5-67.1-155.4-151.5-159.8l63.9-75.6zM256 320A96 96 0 1 1 64 320a96 96 0 1 1 192 0z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period7', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M0 64C0 46.3 14.3 32 32 32H288c11.5 0 22 6.1 27.7 16.1s5.7 22.2-.1 32.1l-224 384c-8.9 15.3-28.5 20.4-43.8 11.5s-20.4-28.5-11.5-43.8L232.3 96H32C14.3 96 0 81.7 0 64z" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-success btn-circle btn-lg" onclick="togglePeriod('period8', this)">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                    <style>
                                                        svg {
                                                            fill: #ffffff
                                                        }
                                                    </style>
                                                    <path d="M304 160c0-70.7-57.3-128-128-128H144C73.3 32 16 89.3 16 160c0 34.6 13.7 66 36 89C20.5 272.3 0 309.8 0 352c0 70.7 57.3 128 128 128h64c70.7 0 128-57.3 128-128c0-42.2-20.5-79.7-52-103c22.3-23 36-54.4 36-89zM176.1 288H192c35.3 0 64 28.7 64 64s-28.7 64-64 64H128c-35.3 0-64-28.7-64-64s28.7-64 64-64h15.9c0 0 .1 0 .1 0h32c0 0 .1 0 .1 0zm0-64c0 0 0 0 0 0H144c0 0 0 0 0 0c-35.3 0-64-28.7-64-64c0-35.3 28.7-64 64-64h32c35.3 0 64 28.7 64 64c0 35.3-28.6 64-64 64z" />
                                                </svg>
                                            </a>
                                        </div>
                                        <input type="hidden" name="periods" id="periods" value="">
                                        <script>
                                            var selectedPeriods = [];

                                            function togglePeriod(period, button) {
                                                var index = selectedPeriods.indexOf(period);

                                                if (index === -1 && selectedPeriods.length < 2) {
                                                    selectedPeriods.push(period);
                                                    button.classList.add('selected');
                                                } else if (index !== -1) {
                                                    selectedPeriods.splice(index, 1);
                                                    button.classList.remove('selected');
                                                }

                                                // อัพเดทอาเรย์ที่ถูกส่งมากับแบบฟอร์ม
                                                document.getElementById('periods').value = selectedPeriods.join(',');
                                            }
                                        </script>
                                        <style>
                                            .btn.selected {
                                                background-color: #08631d;
                                                color: white;
                                            }
                                        </style>


                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="class" class="form-label">ระดับชั้น</label>
                                        <select id="class" name="class" class="form-control">
                                            <option value="1">ม.1</option>
                                            <option value="2">ม.2</option>
                                            <option value="3">ม.3</option>
                                            <option value="4">ม.4</option>
                                            <option value="5">ม.5</option>
                                            <option value="6">ม.6</option>
                                        </select>
                                    </div>
                                    <?php
                                    if (isset($_GET['tb_teacher_id'])) {
                                        $teacher_id = $_GET['tb_teacher_id'];
                                    }

                                    require_once 'connection.php';

                                    $sql = "SELECT * FROM tb_courses WHERE tb_teacher_id = :teacher_id";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_STR);
                                    $stmt->execute();
                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    ?>
                                    <div class="form-group col-lg-6 col-md-6 col-12">
                                        <label for="exampleDataList" class="form-label">วิชา</label>
                                        <select name="subject" id="course-select" class="form-control" onchange="getCourseDetails()">
                                            <option value="">โปรดเลือกวิชา</option>
                                            <?php foreach ($result as $row) : ?>
                                                <option value="<?= $row['tb_course_code'] ?>"><?= $row['tb_course_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <script>
                                        function getCourseDetails() {
                                            var courseSelect = document.getElementById("course-select");
                                            var selectedCourseCode = courseSelect.value;

                                            // ตรวจสอบว่าเลือกวิชาหรือไม่
                                            if (selectedCourseCode !== '') {
                                                // ส่งคำขอ Ajax ไปยังไฟล์ PHP เพื่อดึงข้อมูล
                                                var xhttp = new XMLHttpRequest();
                                                xhttp.onreadystatechange = function() {
                                                    if (this.readyState == 4 && this.status == 200) {
                                                        // รับข้อมูลที่ส่งกลับมาจากไฟล์ PHP
                                                        var courseDetails = this.responseText;

                                                        // แสดงข้อมูลในตำแหน่งที่ต้องการ
                                                        var courseDetailsDiv = document.getElementById("course-details");
                                                        courseDetailsDiv.innerHTML = courseDetails;
                                                    }
                                                };
                                                xhttp.open("GET", "get_course_details.php?course_code=" + selectedCourseCode, true);
                                                xhttp.send();
                                            } else {
                                                // ไม่ต้องทำอะไรเมื่อไม่มีวิชาที่ถูกเลือก
                                                var courseDetailsDiv = document.getElementById("course-details");
                                                courseDetailsDiv.innerHTML = '';
                                            }
                                        }
                                    </script>
                                    <input type="hidden" name="tb_teacher_id" id="tb_teacher_id" value="<?php echo $teacher_id; ?>">
                                </div>
                                <hr>
                                <div id="course-details"></div>
                                <hr>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    ยันยืนการบันทึกข้อมูล
                                </button>
                                <?php
                                require_once 'db_add.php';
                                // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                //     echo '<pre>';
                                //     print_r($_POST);
                                //     echo '</pre>';
                                // }
                                // 
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->
            <?php require_once 'footer.php'; ?>
        </div>
    </div>
</body>
<?php require_once 'script.php'; ?>

</html>