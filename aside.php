<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <?php if ($_SESSION['status'] == 1) : ?>
                    <li>
                        <a href="mainadmin.php"><i class="menu-icon fa fa-user"></i>หน้าหลักแอดมิน</a>
                    </li>
                    <li>
                        <a href="export_date.php"><i class="menu-icon fa fa-save (alias)"></i>แสดงรายชื่อ</a>
                    </li>
                    <li>
                        <a href="import_users.php"><i class="menu-icon fa fa-upload"></i>เพิ่มครู</a>
                    </li>
                    <li>
                        <a href="import_student.php"><i class="menu-icon fa fa-upload"></i>เพิ่มนักเรียน</a>
                    </li>
                    <li>
                        <a href="import_courses.php"><i class="menu-icon fa fa-upload"></i>เพิ่มวิชา</a>
                    </li>
                    <li>
                        <a href="radioexport.php"><i class="menu-icon fa fa-upload"></i>พิมพ์รายงาน</a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['status'] == 0) : ?>
                    <li class="active">
                        <a href="index.php"><i class="menu-icon fa fa-pencil"></i>เช็คชื่อ </a>
                    </li>
                    <!-- <li>
                        <a href="add_student.php"> <i class="menu-icon fa fa-cloud-download"></i>ลงทะเบียนวิชา </a>
                    </li> -->
                    <!-- <li>
                        <a href="main.php"> <i class="menu-icon fa fa-pencil"></i>เช็คชื่อ </a>
                    </li> -->
                    <li>
                        <a href="report.php"> <i class="menu-icon fa fa-paste (alias)"></i>แสดงรายชื่อ </a>
                    </li>
                    <li>
                        <a href="radioreport.php"><i class="menu-icon fa fa-upload"></i>พิมพ์รายงาน</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>