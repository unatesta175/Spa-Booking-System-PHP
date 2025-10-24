<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin-login.php');
}


if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = ($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = ($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $select_admin = $conn->prepare("SELECT * FROM `staffs` WHERE email = ?");
   $select_admin->execute([$email]);

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Emel sudah digunakan!';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Kata laluan pengesahan tidak sepadan!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `staffs`(name,email, password, datetimeregistered) VALUES(?,?,?,?)");
         $insert_admin->execute([$name,  $email, $cpass, $currentDateTime]);
         $message[] = 'Staff baru sudah didaftarkan!';
      }
   }

}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `staffs` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:staff-accounts.php');
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
            max-width: 100% !important;
        }

        .table-responsive {
            font-size: 150.5%;
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

        .form-control-sm ,label{
            font-size: 1.5rem !important;
        }
    </style>
</head>



<?php include '../components/admin_header.php'; ?>



<section class="add-any">

      <h1 class="heading" >Daftar staff</h1>

      <form action="" method="post">
         <div class="flex">
            <div class="inputBox">
               <label for="name">Nama Penuh:</label>
               <input type="text" id="name" name="name" required placeholder="Masukkan nama penuh anda" maxlength="70" class="box">
            </div>
   
            <div class="inputBox">
               <label for="email">Emel :</label>
               <input type="email" id="email" name="email" required placeholder="Masukkan emel" maxlength="70"
                  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="inputBox ">
               <label for="passLogin">Kata Laluan :</label>
               <div class="password-container">
                  <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan "
                     maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                  <i style="font-size:160%;" id="togglePassword" class="fas fa-eye"></i>
               </div>
            </div>
            <div class="inputBox ">
               <label for="passLoginx">Kata Laluan Pengesahan :</label>
               <div class="password-container">
                  <input type="password" id="passLoginx" name="cpass" required
                     placeholder="Masukkan kata laluan pengesahan " maxlength="70" class="box"
                     oninput="this.value = this.value.replace(/\s/g, '')">
                  <i style="font-size:160%;" id="togglePasswordx" class="fas fa-eye"></i>
               </div>
            </div>

            <div class="inputBox">
               <label for="name">Nombor telefon :</label>
               <input type="text" id="phoneno" name="phoneno" required placeholder="Masukkan nombor telefon" maxlength="70"
                  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>


         </div>

         <input type="submit" value="Daftar" class="option-btn" name="submit">
      </form>

   </section>
<div id="bruv" class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-default rounded-0 shadow">
                    <div class="card-body">
                        <h1 class="display-4">Senarai Staff</h1>
                       
                        <div class="col-sm-12 table-responsive">
                            <table id="table" class="table table-striped table-bordered">
                                <thead>
                                    <th>No.</th>
                                    <th>ID Staff</th>
                                    <th>Nama Staff</th>

                                    <th>Email</th>
                                    <th>Tarikh / Masa Daftar</th>
                                    <th>Tindakan</th>
                                </thead>
                                <tbody>
                              <?php
                              $no = 1;
                              $select_accounts = $conn->prepare("SELECT * FROM `staffs`");
                              $select_accounts->execute();
                              if ($select_accounts->rowCount() > 0) {
                                 while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                                    $id = $row['id'];
                                    $name = $row['name'];
                                    $email = $row['email'];
                                    $datetimeregistered = $row['datetimeregistered'];




                                    echo '<tr>
                               <td>' . $no . '</td>
                               <td>' . $id . '</td>
                               <td>' . $name . '</td>
                               <td>' . $email . '</td>
                               <td>' . $datetimeregistered . '</td>
                               <td>
                               <a href="staff-accounts.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk buang akaun ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="delete-btn">Delete</a>
                   
                           
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
</body>

</html>