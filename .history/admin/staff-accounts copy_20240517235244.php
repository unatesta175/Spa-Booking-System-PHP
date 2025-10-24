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

<!DOCTYPE html>
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

<body>

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

   <div class="container">

      <div class="row">
         <div class="col-lg-12">
            <div class="card card-default rounded-0 shadow">
               <div class="card-header">
                  <div class="row">
                     <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                        <h3 class="card-title">Senarai Staff</h3>
                     </div>
                     <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-end">
                  <button type="button" class="option-btn"><a href="addStudentcar.php"
                        style="text-decoration: none;" class="text-light">Tambah user</a></button>
               </div> -->
                  </div>
                  <div style="clear:both"></div>
               </div>
               <div class="card-body">

               <div class="button-container">
                     <button class="btns btn-warning" id="export-btn">
                        <i class="fa-regular fa-file-excel"></i> Eksport ke Excel
                     </button>
                     <button class="btns btn-warning" id="pdf-btn">
                        <i class="fa-regular fa-file-pdf"></i> Eksport ke PDF
                     </button>
                  </div>


                  <div class="row">
                     <div class="col-sm-12 table-responsive">
                        <table id="myTable" class="table table-bordered table-striped">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>ID Staff</th>
                                 <th>Nama Staff</th>
                               
                                 <th>Email</th>
                                 <th>Tarikh / Masa Daftar</th>
                                 <th>Tindakan</th>


                              </tr>

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


   <!-- start of Tables -->
   <script>
      $(document).ready(function () {
         $('#myTable').DataTable({
            "language": {
               "search": "Cari:",
               "lengthMenu": "Papar _MENU_ rekod setiap halaman",
               "zeroRecords": "Tiada data yang sepadan",
               "info": "Papar halaman _PAGE_ dari _PAGES_",
               "infoEmpty": "Tiada rekod",
               "infoFiltered": "(disaring dari jumlah _MAX_ rekod)"
            }
         });
      });
   </script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.1/xlsx.full.min.js"></script>
   <script>
      var table = document.getElementById("myTable");

      document.getElementById("export-btn").addEventListener("click", function () {
         var wb = XLSX.utils.table_to_book(table);
         XLSX.writeFile(wb, "Client Data.xlsx");
      });
   </script>

   <script>
      document.getElementById("pdf-btn").addEventListener("click", function () {
         // Initialize a new PDF document
         const doc = new jspdf.jsPDF();

         // Prepare table headers
         const headers = [["No.", "ID Pengguna", "Nama Pengguna", "Email"]]; // 'Tindakan' column excluded

         // Prepare table body data, excluding the 'Tindakan' column
         const data = [];
         document.querySelectorAll("#myTable tbody tr").forEach(row => {
            const rowData = [];
            row.querySelectorAll("td").forEach((cell, index) => {
               if (index < 4) { // Exclude the last column
                  rowData.push(cell.innerText);
               }
            });
            data.push(rowData);
         });

         // Add table to PDF with customized styling for a more professional look
         doc.autoTable({
            head: headers,
            body: data,
            theme: 'grid', // 'plain' theme for a more formal look; customize further as needed
            styles: {
               font: 'helvetica', // Use a standard, professional font
               fontSize: 10, // Adjust font size for readability
               cellPadding: 5, // Adjust cell padding
               overflow: 'linebreak', // Ensure content fits within cells
               lineColor: [0, 0, 0], // Specify line color for cells
               lineWidth: 0.1, // Specify line width for cells

            },
            headStyles: {
               fillColor: [255, 255, 255], // Set a subtle or white header background
               textColor: [0, 0, 0], // Set text color to black for contrast
               fontStyle: 'bold', // Make header font bold
               lineColor: [0, 0, 0], // Specify line color for cells
               lineWidth: 0.1, // Specify line width for cells
            },
            margin: { top: 20, left: 20, right: 20 }, // Adjust margins to fit your needs
            didDrawCell: (data) => {
               // Conditional styling can be applied here
            },
         });

         // Save the PDF
         doc.save('Senarai Pengguna.pdf');
      });


   </script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>




   <!-- import javascript and css bootsrap bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
      crossorigin="anonymous"></script>
   <!-- import popper to use dropdowns, popovers, or tooltips-->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
      integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
      crossorigin="anonymous"></script>


   <script>const passwordInput = document.getElementById("passLogin");
      const togglePassword = document.getElementById("togglePassword");

      togglePassword.addEventListener("click", function () {
         if (passwordInput.type === "password") {
            passwordInput.type = "text";
            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");
         } else {
            passwordInput.type = "password";
            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");
         }
      });</script>

   <script>const passwordInputx = document.getElementById("passLoginx");
      const togglePasswordx = document.getElementById("togglePasswordx");

      togglePasswordx.addEventListener("click", function () {
         if (passwordInputx.type === "password") {
            passwordInputx.type = "text";
            togglePasswordx.classList.remove("fa-eye");
            togglePasswordx.classList.add("fa-eye-slash");
         } else {
            passwordInputx.type = "password";
            togglePasswordx.classList.remove("fa-eye-slash");
            togglePasswordx.classList.add("fa-eye");
         }
      });</script>


   <!-- End of Tables -->









   <script src="../js/admin_script.js"></script>

</body>

</html>