<?php
if (
    isset($_POST['id'])
    && isset($_POST['cause'])
    && isset($_POST['custom_cause'])
) {
    require_once 'connect.php';
    $id = $_POST['id'];
    $cause = $_POST['cause'];
    $custom_cause = $_POST['custom_cause'];
    //sql update
    $stmt = $conn->prepare("UPDATE  ck_checking SET cause=:cause, custom_cause=:custom_cause WHERE id=:id");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->bindParam(':cause', $cause, PDO::PARAM_STR);
    $stmt->bindParam(':custom_cause', $custom_cause, PDO::PARAM_STR);
    $stmt->execute();

    echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

    if ($stmt->rowCount() >= 0) {

        echo '<script>
        swal({
          title: "Edit Data Success",
          text: "success",
          type: "success",
          timer: 1500,
          showConfirmButton: false
        }, function(){
          window.location = "check_date.php";
        });
      </script>';
    } else {
        echo '<script>
        swal({
          title: "Edit data fail",
          text: "fail",
          type: "fail",
          timer: 1500,
          showConfirmButton: false
        }, function(){
          window.location.href = "date_u.php";
        });
      </script>';
    }
    $conn = null; //close connect db
} //isset
