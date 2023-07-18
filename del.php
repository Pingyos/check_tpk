<?php
if (isset($_GET['excel_id'])) {
    require_once 'connect.php';
    //ประกาศตัวแปรรับค่าจาก param method get
    $excel_id = $_GET['excel_id'];
    $stmt = $conn->prepare('DELETE FROM tb_users WHERE excel_id=:excel_id');
    $stmt->bindParam(':excel_id', $excel_id, PDO::PARAM_INT);
    $stmt->execute();

    //  sweet alert 
    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    if ($stmt->rowCount() == 1) {
        echo '<script>
             setTimeout(function() {
              swal({
                  title: "Del Data Success",
                  type: "success"
              }, function() {
                  window.location = "import.php";
              });
            }, 200);
        </script>';
    } else {
        echo '<script>
             setTimeout(function() {
              swal({
                  title: "Del Data Error",
                  type: "error"
              }, function() {
                  window.location = "index.php";
              });
            }, 200);
        </script>';
    }
    $conn = null;
} //isset
