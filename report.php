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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Student Name</th>
                                                <th>Absent</th>
                                                <th>Absence Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tiger Nixon</td>
                                                <td>System Architect</td>
                                                <td>Edinburgh</td>
                                                <td>Edinburgh</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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