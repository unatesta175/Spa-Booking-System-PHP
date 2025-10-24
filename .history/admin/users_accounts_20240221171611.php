<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:users_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

    
   <!-- Starting of Data tables requirements -->
   
    <!-- Bootstrap The most important for Data Tables -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- jQuery -->

    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css"/>

     <!-- Font Awesome  (Kena Sentiasa ditutup jangan kasi buka, nanti user profile icon jadi kecik gila)-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<!-- Ending of Data tables requirements -->



  


   
<!-- Starting of Data tables requirements -->
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

      .container{ padding:2rem;}

      .table-responsive{font-size:162.5%;}
  .card-title{font-size:350.5%;}

  .button-container {
   display: flex;
   justify-content: center; /* Center buttons horizontally */
   align-items: center; /* Center buttons vertically */
   gap: 10px; /* Space between buttons */
}

/* For smaller screens, stack buttons vertically */
@media (max-width: 600px) { /* Adjust the breakpoint as needed */
   .button-container {
       flex-direction: column;
   }
}


  
  
    </style>
    <!-- Ending of Data tables requirements -->
</head>
<body>

  

<?php include '../components/admin_header.php'; ?>
<!-- start of Tables -->
<div class="container">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="card card-default rounded-0 shadow">
				<div class="card-header">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6">
							<h3 class="card-title">Senarai Pengguna</h3>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-end">
							<button type="button" class="option-btn"><a
									href="addStudentcar.php" style="text-decoration: none;"class="text-light">Tambah user</a></button>
						</div>
					</div>
					<div style="clear:both"></div>
				</div>
				<div class="card-body">

            

					<div class="row">
					<div class="col-sm-12 table-responsive">
							<table id="myTable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No.</th>
										<th>ID Penggguna</th>
										<th>Nama Pengguna</th>
										<th>Email</th>
										<th>Tindakan</th>
									</tr>
									
								</thead>
								<tbody>
									<?php
									$no = 1;
									$select_accounts = $conn->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($row = $select_accounts->fetch(PDO::FETCH_ASSOC)){

									
											$id = $row['id'];
											$name = $row['name'];
											$email = $row['email'];
											
											
		

											echo '<tr>
                							<td>' . $no . '</td>
                							<td>' . $id . '</td>
                							<td>' . $name . '</td>
                							<td>' . $email . '</td>
											
               								<td>
                                       <center><a class=" underline" style="font-size: 1.5em;" href="users_accounts.php?delete=' . $id . '" onclick="return confirm(`delete this account? the user related information will also be delete!"> <center><i class=" fa fa-trash" style="color:red">
                                       </i></center></a>&nbsp;&nbsp;&nbsp;&nbsp;</center>
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







<script src="../js/admin_script.js"></script>
   
</body>
</html>