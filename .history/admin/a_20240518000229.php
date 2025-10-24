<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_user = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
    $delete_user->execute([$delete_id]);
    // $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    // $delete_orders->execute([$delete_id]);
    // $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
    // $delete_messages->execute([$delete_id]);
    // $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    // $delete_cart->execute([$delete_id]);
    // $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    // $delete_wishlist->execute([$delete_id]);
    // header('location:record.php');
    $_SESSION['delete'] = true;
}




if (isset($_SESSION['delete'])) {
    // Prepare JavaScript for showing the Sweet Alert
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
                   
                });
            }
          </script>";
    unset($_SESSION['delete']); // Unset the flag

} else {
    $bookdeleteScript = "";
}


if (isset($_SESSION['book_success'])) {
    // Prepare JavaScript for showing the Sweet Alert
    $bookSuccessScript = "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Berjaya!',
                    text: 'Permohonan tempahan anda sudah berjaya dibuat!',
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
    unset($_SESSION['book_success']); // Unset the flag
} else {
    $bookSuccessScript = "";
}
?>


<!doctype html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include '../components/functions.php';
   includeHeaderAdmin()
      ?>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

   <!-- Starting of Data tables requirements -->

   <!-- Bootstrap The most important for Data Tables -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
      integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
      integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>
   <!-- jQuery -->

   <script src="../js/jquery.dataTables.min.js"></script>
   <script src="../js/dataTables.bootstrap.min.js"></script>
   <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />

   <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
   <!-- Ending of Data tables requirements -->

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
      }

      .table-responsive {
         font-size: 162.5%;
      }

      .card-title {
         font-size: 300.5%;
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
   </style>
   <!-- Ending of Data tables requirements -->
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