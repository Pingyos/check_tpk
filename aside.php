<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">

                <?php if ($_SESSION['status'] == 1) : ?>
                    <li>
                        <a href="mainadmin.php"><i class="menu-icon fa fa-user"></i>หน้าหลักแอดมิน</a>
                    </li>
                    <li>
                        <a href="export.php"><i class="menu-icon fa fa-save (alias)"></i>Export</a>
                    </li>
                    <li>
                        <a href="import_users.php"><i class="menu-icon fa fa-plus-circle"></i>Import Teacher</a>
                    </li>
                    <li>
                        <a href="import_student.php"><i class="menu-icon fa fa-plus-circle"></i>Import Student</a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['status'] == 0) : ?>
                    <li class="active">
                        <a href="index.php"><i class="menu-icon fa fa-laptop"></i>หน้าหลัก </a>
                    </li>
                    <!-- <li>
                        <a href="add_student.php"> <i class="menu-icon fa fa-cloud-download"></i>ลงทะเบียนวิชา </a>
                    </li> -->
                    <li>
                        <a href="main.php"> <i class="menu-icon fa fa-pencil"></i>เช็คชื่อ </a>
                    </li>
                    <li>
                        <a href="report.php"> <i class="menu-icon fa fa-paste (alias)"></i>รายงาน </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>