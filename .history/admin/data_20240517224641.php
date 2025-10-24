<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin-login.php');
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

        /* Add the new rule to increase font size in the table */
        #bruv {
            font-size: 1em;
            /* Adjust the font size as needed */
        }
    </style>
</head>



<?php include '../components/admin_header.php'; ?>

<div id="bruv" class="section" style="width:100% !important;">
    <div class="container" style="width:100% !important;">
        <div class="row"  style="width:100% !important;">
            <div class="col-lg-12">
                <div class="card card-default rounded-0 shadow"  style="width:100% !important;">
                    <div class="card-body "  style="width:100% !important;">
                        <h1 class="display-4"  style="width:100% !important;">Rekod Tempahan</h1>
                        <div class="row" style="width:100% !important;">
                            <div class="col-sm-12 table-responsive" style="width:100% !important;">
                                <table id="table" class="table table-striped table-bordered" style="width:100% !important;">
                                    <thead>
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