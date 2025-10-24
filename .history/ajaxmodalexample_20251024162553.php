<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
};

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');

    $username = $_POST['username'];
    $username = mb_convert_case($username, MB_CASE_TITLE, "UTF-8");
    $username = htmlspecialchars(strip_tags(trim($username)), ENT_QUOTES, 'UTF-8');

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = ($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = ($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');

    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE username = ?");
    $select_admin->execute([$username]);

    if ($select_admin->rowCount() > 0) {
        $message[] = 'Username sudah digunakan!';
        $_SESSION['booking_success'] = 3;
    } else {
        if ($pass != $cpass) {
            $message[] = 'Kata laluan pengesahan tidak sepadan!';
            $_SESSION['booking_success'] = 2;
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name,username,email, password, datetimeregistered) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$name, $username, $email, $cpass, $currentDateTime]);
            $message[] = 'Admin baru sudah didaftarkan!';
            $_SESSION['booking_success'] = 1;
        }
    }
   
    
    // Function to increment ID



}

if ($_SESSION['booking_success'] == 1) {
    // Prepare JavaScript for showing the Sweet Alert
    $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Admin berjaya didaftar!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                   
                });
            }
          </script>";
    unset($_SESSION['booking_success']); // Unset the flag
}else if ($_SESSION['booking_success'] == 2) {
    // Prepare JavaScript for showing the Sweet Alert
    $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Tidak berjaya!',
                    text: 'Kata laluan pengesahan tidak sepadan!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                   
                });
            }
          </script>";
    unset($_SESSION['booking_success']); // Unset the flag
}else if ($_SESSION['booking_success'] == 3) {
    // Prepare JavaScript for showing the Sweet Alert
    $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Tidak berjaya!',
                    text: 'Username telah digunakan!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                   
                });
            }
          </script>";
    unset($_SESSION['booking_success']); // Unset the flag
}else {
    $SuccessScript = "";
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Modal Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php echo $SuccessScript; ?>

    <div class="container mt-5">
        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Daftar Admin
        </button>

        <!-- The Modal -->
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Maklumat Admin baru</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Form to submit data -->
                        <form action="" method="post">
                            <div class="flex">
                                <div class="inputBox">
                                    <label for="name">Nama Penuh :</label>
                                    <input type="text" id="name" name="name" required placeholder="Masukkan nama penuh anda" maxlength="70" class="box">
                                </div>
                                <div class="inputBox">
                                    <label for="username">Username :</label>
                                    <input type="text" id="username" name="username" required placeholder="ex : ilyas175" maxlength="70" class="box">
                                </div>
                                <div class="inputBox">
                                    <label for="email">Emel :</label>
                                    <input type="email" id="email" name="email" required placeholder="Masukkan emel" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                </div>
                                <div class="inputBox ">
                                    <label for="passLogin">Kata Laluan:</label>
                                    <div class="password-container">
                                        <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        <i style="font-size:160%;" id="togglePassword" class="fas fa-eye"></i>
                                    </div>
                                </div>
                                <div class="inputBox ">
                                    <label for="passLoginx">Kata Laluan Pengesahan:</label>
                                    <div class="password-container">
                                        <input type="password" id="passLoginx" name="cpass" required placeholder="Masukkan kata laluan pengesahan " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                                        <i style="font-size:160%;" id="togglePasswordx" class="fas fa-eye"></i>
                                    </div>
                                </div>


                            </div>

                            <input type="submit" value="Daftar" class="option-btn" name="submit">
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="js/sweetalert2.all.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>