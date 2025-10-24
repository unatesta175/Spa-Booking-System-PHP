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

    $username = $_POST['username'];
    $username = mb_convert_case($username, MB_CASE_TITLE, "UTF-8");
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = ($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = ($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');

    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admin->execute([$name]);

    if ($select_admin->rowCount() > 0) {
        $message[] = 'Username sudah digunakan!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Kata laluan pengesahan tidak sepadan!';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admins`(name,username,email, password, datetimeregistered) VALUES(?,?,?,?,?)");
            $insert_admin->execute([$name, $username, $email, $cpass, $currentDateTime]);
            $message[] = 'Admin baru sudah didaftarkan!';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
    $delete_admins->execute([$delete_id]);
    header('location:admin-accounts.php');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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
            font-size: 150.5%;
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
    <!-- Ending of Data tables requirements -->
</head>

<body>

    <?php include '../components/admin_header.php'; ?>





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
                                <button style="margin:1rem 0;" href="calendar.php" class="btns btn-warning"><i class="fas fa-calendar-days"></i> Lihat Tempahan di kalendar</button>
                                <button class="btns btn-warning" href="booking.php" >
                                    <i class="fa-regular fa-calendar-plus"></i> Tambah Tempahan
                                </button>
                            </div>




                            <div class="row">

                                <div class="col-sm-12 table-responsive">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>

                                                <th>No Tempahan </th>
                                                <th>Tarikh Tempahan </th>
                                                <th>Pakej Terapi</th>
                                                <th>Nama Pakar Terapi</th>
                                                <th>Slot Masa Tempahan</th>
                                                <th>Tempoh Masa</th>
                                                <th style="width:10%;">Harga</th>


                                                <th>Status Tempahan</th>
                                                <th>Status Bayaran</th>
                                                <th>Tindakan</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $select_accounts = $conn->prepare("SELECT * FROM `bookings` ");
                                            $select_accounts->execute();
                                            if ($select_accounts->rowCount() > 0) {
                                                while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                                                    $id = $row['booking_id'];
                                                    $date = $row['date'];
                                                    $timeslot = $row['timeslot'];
                                                    $duration = $row['duration'];
                                                    $pay_amount = $row['pay_amount'];
                                                    $staff_id = $row['staff_id'];
                                                    $service_id = $row['service_id'];
                                                    $pay_stat = $row['pay_stat'];
                                                    $bookingstat = $row['bookingstat'];
                                                    $billcode = $row['billcode'];

                                                    $select_accounts1 = $conn->prepare("SELECT * FROM `services` WHERE id = ?");
                                                    $select_accounts1->execute([$service_id]);
                                                    if ($select_accounts1->rowCount() > 0) {
                                                        while ($row1 = $select_accounts1->fetch(PDO::FETCH_ASSOC)) {


                                                            $servicename = $row1['name'];
                                                        }
                                                    }

                                                    $select_accounts2 = $conn->prepare("SELECT * FROM `staffs` WHERE id = ?");
                                                    $select_accounts2->execute([$staff_id]);
                                                    if ($select_accounts2->rowCount() > 0) {
                                                        while ($row2 = $select_accounts2->fetch(PDO::FETCH_ASSOC)) {


                                                            $staffname = $row2['name'];
                                                        }
                                                    }

                                                    // $rmx100 = ($pay_amount * 100);
                                                    // $some_data = array(
                                                    //    'userSecretKey'=> 'm8zfj65c-2fzo-gq3b-rwhw-xvneusqy7wuy',
                                                    //    'categoryCode'=> '2n8qqo61',
                                                    //    'billName' => $id,
                                                    //    'billDescription' => $name ,
                                                    //    'billPriceSetting' => 1,
                                                    //    'billPayorInfo' => 1,
                                                    //    'billAmount' => $rmx100,
                                                    //    'billReturnUrl' => 'index.php',
                                                    //    'billCallbackUrl' => '',
                                                    //    'billExternalReferenceNo' => '',
                                                    //    'billTo' => $name,
                                                    //    'billEmail' => $email,
                                                    //    'billPhone' => $phoneno,
                                                    //    'billSplitPayment' => 0,
                                                    //    'billSplitPaymentArgs' => '',
                                                    //    'billPaymentChannel' => 0,
                                                    // );
                                                    // $curl = curl_init();
                                                    // curl_setopt($curl, CURLOPT_POST, 1);
                                                    // curl_setopt($curl, CURLOPT_URL, 'https://toyyibpay.com/index.php/api/createBill');  
                                                    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                                    // curl_setopt($curl, CURLOPT_POSTFIELDS, $some_data);
                                                    // $result = curl_exec($curl);
                                                    // $info = curl_getinfo($curl);  
                                                    // curl_close($curl);
                                                    // $obj = json_decode($result,true);
                                                    // $billcode=$obj[0]['BillCode'];


                                                    echo '<tr>
                            
                               <td>' . $id . '</td>
                               <td>' . $date . '</td>
                               <td>' . $servicename . '</td>
                               <td>' . $staffname . '</td>
                               <td>' . $timeslot . '</td>
                               <td >' . formatDuration($duration) . '</td>
                               <td> RM ' . $pay_amount . '</td>
                               
                              
                             

                               <td>' . $bookingstat . '</td>
                               <td>'; ?> <?php

                                                    if ($pay_stat == 'Belum Bayar') {
                                                        echo '<span class="bdg bdgdanger">Belum Bayar</span>';
                                                    } else {
                                                        echo '<span class="bdg bdgsuccess">Telah Bayar</span>';
                                                    }

                                                    echo '
</td>



                               <td>
      <a href="record.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk batalkan tempahan ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="tblbtn tblremove"><i style="margin: 0em 0.2em;"class="fa fa-ban"></i>Batal</a>
      <a href="https://toyyibpay.com/' . $billcode . '" class="tblbtn tblpay"><i style="margin: 0em 0.2em;"class="fa fa-credit-card"></i>Bayar</a>
  
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
                            <div>
                                <a href="service-package.php" class="btns btn-secondary"><i class="fas fa-arrow-left"></i>Kembali ke
                                    Pakej</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <!-- End of Tables -->
    </div>


    <!-- start of Tables -->
    <script>
        $(document).ready(function() {
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

        document.getElementById("export-btn").addEventListener("click", function() {
            var wb = XLSX.utils.table_to_book(table);
            XLSX.writeFile(wb, "Tempahan.xlsx");
        });
    </script>

    <script>
        document.getElementById("pdf-btn").addEventListener("click", function() {
            // Initialize a new PDF document
            const doc = new jspdf.jsPDF();

            // Prepare table headers
            const headers = [
                ["No. Tempahan", "Tarikh", "Rawatan", "Pakar Terapi", "Slot Masa", "Harga", "Status Tempahan", "Status Bayaran"]
            ]; // 'Tindakan' column excluded

            // Prepare table body data, excluding the 'Tindakan' column
            const data = [];
            document.querySelectorAll("#myTable tbody tr").forEach(row => {
                const rowData = [];
                row.querySelectorAll("td").forEach((cell, index) => {
                    if (index < 8) { // Exclude the last column
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
                margin: {
                    top: 20,
                    left: 20,
                    right: 20
                }, // Adjust margins to fit your needs
                didDrawCell: (data) => {
                    // Conditional styling can be applied here
                },
            });

            // Save the PDF
            doc.save('Senarai Tempahan.pdf');
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>




    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>




    <!-- End of Tables -->









    <script src="../js/admin_script.js"></script>

</body>

</html>