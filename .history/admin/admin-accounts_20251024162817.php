<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
    exit;
}

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');

    $username = $_POST['username'];
    $username = mb_convert_case($username, MB_CASE_TITLE, "UTF-8");
    $username = htmlspecialchars(strip_tags(trim($username)), ENT_QUOTES, 'UTF-8');

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];
    $pass = htmlspecialchars(strip_tags(trim($pass)), ENT_QUOTES, 'UTF-8');
    $cpass = $_POST['cpass'];
    $cpass = htmlspecialchars(strip_tags(trim($cpass)), ENT_QUOTES, 'UTF-8');

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');
    $defaultDateTime = "0000-00-00 00:00:00";
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE username = ?");
    $select_admin->execute([$username]);

    if ($select_admin->rowCount() > 0) {
        $_SESSION['booking_success'] = 3; // Username already exists
    } else {
        if ($pass != $cpass) {
            $_SESSION['booking_success'] = 2; // Passwords do not match
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name,username,email, password, datetimeregistered ,updated_at) VALUES(?,?,?,?,?,?)");
            $insert_admin->execute([$name, $username, $email, $cpass, $currentDateTime,  $defaultDateTime]);
            $_SESSION['booking_success'] = 1; // Successfully registered
        }
    }
}

// Prepare JavaScript for showing the Sweet Alert
if (isset($_SESSION['booking_success'])) {
    if ($_SESSION['booking_success'] == 1) {
        $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Admin berjaya didaftar!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        // Define a class for the SweetAlert popup
                        popup: 'my-custom-popup',
                        // Define a class for the SweetAlert title
                        title: 'my-custom-title',
                        // Define a class for the SweetAlert text
                        text: 'my-custom-text',
                        // Define a class for the SweetAlert confirm button
                        confirmButton: 'my-custom-confirm-button',
                        // Define a class for the SweetAlert cancel button
                        cancelButton: 'my-custom-cancel-button'
                    }
                });
            }
          </script>";
    } elseif ($_SESSION['booking_success'] == 2) {
        $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Tidak berjaya!',
                    text: 'Kata laluan pengesahan tidak sepadan!',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        // Define a class for the SweetAlert popup
                        popup: 'my-custom-popup',
                        // Define a class for the SweetAlert title
                        title: 'my-custom-title',
                        // Define a class for the SweetAlert text
                        text: 'my-custom-text',
                        // Define a class for the SweetAlert confirm button
                        confirmButton: 'my-custom-confirm-button',
                        // Define a class for the SweetAlert cancel button
                        cancelButton: 'my-custom-cancel-button'
                    }
                });
            }
          </script>";
    } elseif ($_SESSION['booking_success'] == 3) {
        $SuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Tidak berjaya!',
                    text: 'Username telah digunakan!',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        // Define a class for the SweetAlert popup
                        popup: 'my-custom-popup',
                        // Define a class for the SweetAlert title
                        title: 'my-custom-title',
                        // Define a class for the SweetAlert text
                        text: 'my-custom-text',
                        // Define a class for the SweetAlert confirm button
                        confirmButton: 'my-custom-confirm-button',
                        // Define a class for the SweetAlert cancel button
                        cancelButton: 'my-custom-cancel-button'
                    }
                });
            }
          </script>";
    }
    unset($_SESSION['booking_success']); // Unset the flag
} else {
    $SuccessScript = "";
}

// Echo the script so it gets included in the HTML output
echo $SuccessScript;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
    $delete_admins->execute([$delete_id]);
    $_SESSION['delete'] = true;
}

if (isset($_SESSION['delete'])) {
    // Prepare JavaScript for showing the Sweet Alert
    unset($_SESSION['delete']);
    $bookdeleteScript = "<script>
           window.onload = function() {
               Swal.fire({
                   title: 'Berjaya!',
                   text: 'Tempahan berjaya dibatalkan!',
                   icon: 'success',
                   confirmButtonText: 'OK',
                   customClass: {
                       // Define a class for the SweetAlert popup
                       popup: 'my-custom-popup',
                       // Define a class for the SweetAlert title
                       title: 'my-custom-title',
                       // Define a class for the SweetAlert text
                       text: 'my-custom-text',
                       // Define a class for the SweetAlert confirm button
                       confirmButton: 'my-custom-confirm-button',
                       // Define a class for the SweetAlert cancel button
                       cancelButton: 'my-custom-cancel-button'
                   }
                  
               }).then(function() {
                   // Reload the page after the Sweet Alert is closed
                   window.location.href = window.location.pathname;
               });
           }
         </script>";
    unset($_SESSION['delete']); // Unset the flag
} else {
    $bookdeleteScript = "";
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include '../components/functions.php';
    includeHeaderAdmin()
    ?>

    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/DataTables/Buttons-1.5.6/css/buttons.bootstrap4.min.css">




    <style>
        @media only screen and (max-width: 300px) {
            table {
                width: 100%;
            }

            thead {
                display: none;
            }

            tr:nth-of-type(2n) {
                background-color: inherit;
            }

            tr td:first-child {
                background: #f0f0f0;
                font-weight: bold;
                font-size: 1.3em;
                text-align: center;
            }

            tbody td {
                display: block;
                text-align: center;
            }

            tbody td:before {
                content: attr(data-th);
                display: block;
                text-align: left;
            }
        }

        .container {
            padding: 2rem;
            max-width: 90% !important;
        }

        .table-responsive {
            font-size: 140.5%;
        }

        .card-title {
            font-size: 300.5%;
        }




        /* For smaller screens, stack buttons vertically */
        @media (max-width: 600px) {

            /* Adjust the breakpoint as needed */
            .button-container {
                flex-direction: column;
            }
        }


        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .password-container i:hover {
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: center;
            /* Center buttons horizontally */
            align-items: center;
            /* Center buttons vertically */
            gap: 10px;
            /* Space between buttons */
            margin-bottom: 3em;


        }

        /* Add the new rule to increase font size in the table */
        #bruv {
            font-size: 1em;
            /* Adjust the font size as needed */
        }

        .dt-buttons .btn {
            font-size: 1em;
            /* Adjust the font size as needed */
            display: inline-block;
            font-weight: 600;
            color: #fff !important;
            text-align: center;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 0px;
            transition: background-color 0.3s ease !important;
            border: none;

        }

        .form-control-sm,
        label {
            font-size: 1.5rem !important;
        }

        div.dt-button-collection.dropdown-menu .dt-button {

            font-size: 1.5rem !important;
        }
    </style>
</head>



<?php include '../components/admin_header.php'; ?>

<?php echo $bookdeleteScript; ?>

<!-- <section class="add-any">

    <h1 class="heading">Daftar Admin</h1>

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
</section> -->

<div id="bruv" class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default rounded-0 shadow">
                    <div class="card-body">
                        <h1 class="display-4">Senarai Admin</h1>
                        <div style="margin: 1.5rem;">
                            <button type="button" class="btns btn-warning" data-toggle="modal" data-target="#myModal"><i style="margin: 0em 0.2em;"class="fa fa-user-plus"></i>
                                Daftar Admin
                            </button>
                        </div>
                        <div class="col-sm-12 table-responsive">
                            <table id="table" class="table table-striped table-bordered">
                                <thead>
                                    <!-- <th>No.</th>
                                 <th>ID Admin</th> -->
                                    <th>Nama Admin</th>
                                    <th>Username Admin</th>
                                    <th>Email</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                    <th>Tindakan</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $select_accounts = $conn->prepare("SELECT * FROM `admins`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                                            $id = $row['id'];
                                            $name = $row['name'];
                                            $username = $row['username'];
                                            $email = $row['email'];
                                            $datetimeregistered = $row['datetimeregistered'];
                                            $updated_at = $row['updated_at'];




                                            echo '<tr>
                    
                               <td>' . $name . '</td>
                               <td>' . $username . '</td>
                               <td>' . $email . '</td>
                               <td>' . $datetimeregistered . '</td>
                               <td>' . $updated_at . '</td>
                               <td>
                               <a href="admin-accounts.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk buang akaun ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="tblbtn tblremove"><i style="margin: 0em 0.2em;"class="fa fa-ban"></i>Delete</a>
                               <a href="update-admin.php?update=' . $id . '"  class="tblbtn tbledit"><i style="margin: 0em 0.2em;"class="fa fa-pen"></i>Kemaskini</a>
                   
                           
                               </td>
                               
                               </tr>';
                                            $no++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



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
            <div class="modal-body add-any">
                <!-- Form to submit data -->
                <form action="" style="box-shadow:none;" method="post">
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
                <button type="button" class="btns btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>

<!-- Datatables -->
<script src="../assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script src="../assets/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js"></script>

<script src="../assets/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
<script src="../assets/DataTables/Buttons-1.5.6/js/buttons.bootstrap4.min.js"></script>
<script src="../assets/DataTables/JSZip-2.5.0/jszip.min.js"></script>
<script src="../assets/DataTables/pdfmake-0.1.36/pdfmake.min.js"></script>
<script src="../assets/DataTables/pdfmake-0.1.36/vfs_fonts.js"></script>
<script src="../assets/DataTables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
<script src="../assets/DataTables/Buttons-1.5.6/js/buttons.print.min.js"></script>
<script src="../assets/DataTables/Buttons-1.5.6/js/buttons.colVis.min.js"></script>

<script src="js/sweetalert2.all.min.js"></script>
<script src="../js/admin_script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        var table = $('#table').DataTable({
            buttons: ['copy', 'csv', 'print', 'excel', 'pdf', 'colvis'],
            dom: "<'row'<'col-md-3'l><'col-md-5'B><'col-md-4'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row'<'col-md-5'i><'col-md-7'p>>",
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],

        });

        table.buttons().container()
            .appendTo('#table_wrapper .col-md-5:eq(0)');
    });
</script>

<script>
    const passwordInput = document.getElementById("passLogin");
    const togglePassword = document.getElementById("togglePassword");

    togglePassword.addEventListener("click", function() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
        }
    });
</script>

<script>
    const passwordInputx = document.getElementById("passLoginx");
    const togglePasswordx = document.getElementById("togglePasswordx");

    togglePasswordx.addEventListener("click", function() {
        if (passwordInputx.type === "password") {
            passwordInputx.type = "text";
            togglePasswordx.classList.remove("fa-eye");
            togglePasswordx.classList.add("fa-eye-slash");
        } else {
            passwordInputx.type = "password";
            togglePasswordx.classList.remove("fa-eye-slash");
            togglePasswordx.classList.add("fa-eye");
        }
    });
</script>
</body>

</html>