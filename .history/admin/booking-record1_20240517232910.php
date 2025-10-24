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
    <link rel="stylesheet" href=" https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
 
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

    <?php echo $bookSuccessScript;
    echo $bookdeleteScript; ?>



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
                                <a style="margin:1rem 0;" href="calendar.php" class="btns btn-warning"><i class="fas fa-calendar-days"></i> Lihat Tempahan di kalendar</a>
                                <a class="btns btn-warning" href="booking.php">
                                    <i class="fa-regular fa-calendar-plus"></i> Tambah Tempahan
                                </a>
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
        $(document).ready(function () {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                responsive: true,
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>



    <!-- import javascript and css bootsrap bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <!-- import popper to use dropdowns, popovers, or tooltips-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- End of Tables -->



    <script src="js/sweetalert2.all.min.js"></script>
    <script src="../js/admin_script.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    
  
</body>

</html>

