<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
;



include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <?php
   include './components/functions.php';
   includeHeader();
   ?>
   <!-- Starting of Data tables requirements -->

  
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

   <script src="js/jquery.dataTables.min.js"></script>
   <script src="js/dataTables.bootstrap.min.js"></script>
   <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />

   <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
   <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
   <!-- Ending of Data tables requirements -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">



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
            font-size: 12em;
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
         font-size: 140.5%;
      }

      .card-title {
         font-size: 350.5%;
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


   </style>
</head>

<body>

   <?php include 'components/user-header.php'; 
   ?>
   <?php echo $loginSuccessScript; ?>
   <div class="section">
      <br>
       <!-- start of Tables -->
   <div class="container">

<div class="row">
   <div class="col-lg-12">
      <div class="card card-default rounded-0 shadow">
         <div class="card-header">
            <div class="row">
               <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
                  <h3 class="card-title">Rekod Tempahan</h3>
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
               <button class="option-btn" id="export-btn">
                  <i class="fa-regular fa-file-excel"></i> Eksport ke Excel
               </button>
               <button class="option-btn" id="pdf-btn">
                  <i class="fa-regular fa-file-pdf"></i> Eksport ke PDF
               </button>
            </div>
            

            <div class="row">
               <div class="col-sm-12 table-responsive">
                  <table id="myTable" class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           
                           <th>No Tempahan </th>
                           <th>Tarikh Tempahan </th>
                           <th>Slot Masa Tempahan</th>
                           <th>Tempoh Masa</th>
                           <th>Harga</th>
                           <th>Nama Pakar Terapi</th>
                           <th>Pakej Terapi</th>
                           <th>Status Tempahan</th>
                           <th>Status Bayaran</th>
                           <th>Tindakan</th>
                        </tr>

                     </thead>
                     <tbody>
                        <?php
                        $no = 1;
               $select_accounts = $conn->prepare("SELECT * FROM `bookings` WHERE client_id = ?");
                    $select_accounts->execute([$user_id]);
                        if ($select_accounts->rowCount() > 0) {
                           while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                              $id = $row['id'];
                              $date = $row['date'];
                              $timeslot = $row['timeslot'];
                              $duration = $row['duration'];
                              $pay_amount = $row['pay_amount'];
                              $staff_id = $row['staff_id'];
                              $service_id = $row['service_id'];
                              $pay_stat = $row['pay_stat'];
                              $bookingstat = $row['bookingstat'];
                              
                            
                             
                              
                              echo '<tr>
                            
                               <td>' . $id . '</td>
                               <td>' . $date . '</td>
                               <td>' . $timeslot . '</td>
                               <td>' . $duration . '</td>
                               <td>' . $service_id . '</td>
                               <td>' . $phoneno . '</td>
                               <td>' . $datebirth . '</td>
                               <td>' . $pay_stat . '</td>
                               <td>' .$bookingstat . '</td>
                               
                           
                               <td>
      <a href="client-accounts.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk buang akaun ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="delete-btn">Delete</a>

  
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
<!-- End of Tables -->






   <?php include 'components/footer.php'; ?>

 


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
    const doc = new jspdf.jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const margin = 5; // Constant margin for the page
    const usableWidth = pageWidth - (2 * margin); // Calculate usable width

    const headers = [["No.", "ID", "Nama", "I/C", "Emel", "No.Tel", "Tarikh Lahir", "Alamat", "Status Perkahwinan", "Agama", "Pekerjaan", "Syarikat", "FBN", "IGN", "Alahan"]];
    const numberOfColumns = headers[0].length;
    const columnWidth = usableWidth / numberOfColumns; // Evenly distribute column width

    // Gather data, excluding 'Tindakan' column
    const data = [];
    document.querySelectorAll("#myTable tbody tr").forEach(row => {
        const rowData = [];
        row.querySelectorAll("td").forEach((cell, index, cells) => {
            if (index < cells.length - 1) { // Exclude the 'Tindakan' column
                rowData.push(cell.innerText);
            }
        });
        data.push(rowData);
    });

    doc.autoTable({
        head: headers,
        body: data,
        theme: 'grid',
        startY: 20, // Start 20 units down from the top of the page
        styles: {
            fontSize: 3, // Reduced font size for content
            cellPadding: 2, // Constant cell padding
            overflow: 'linebreak', // Allow text wrapping for content
            cellWidth: columnWidth, // Apply calculated column width
        },
        headStyles: {
            fillColor: [255, 255, 255],
            textColor: [0, 0, 0],
            fontStyle: 'bold',
            overflow: 'visible', // Prevent text wrapping in headers
            cellPadding: 2, // Ensure padding is consistent with body
            cellWidth: columnWidth, // Apply calculated column width to headers as well
        },
        margin: { horizontal: margin, top: 20, bottom: 20 }, // Apply constant margins
    });

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



<!-- End of Tables -->

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

   <script src="js/script.js"></script>

</body>

</html>