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
    $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
    $ic = $_POST['ic'];
    $ic = htmlspecialchars(strip_tags(trim($ic)), ENT_QUOTES, 'UTF-8');
    $email = $_POST['email'];
    $email = htmlspecialchars(strip_tags(trim($email)), ENT_QUOTES, 'UTF-8');
    $pass = ($_POST['pass']);
    $pass = htmlspecialchars(strip_tags(trim($pass)), ENT_QUOTES, 'UTF-8');
    $cpass = ($_POST['cpass']);
    $cpass = htmlspecialchars(strip_tags(trim($cpass)), ENT_QUOTES, 'UTF-8');
    $phoneno = $_POST['phoneno'];
    $phoneno = htmlspecialchars(strip_tags(trim($phoneno)), ENT_QUOTES, 'UTF-8');
    $datebirth = $_POST['datebirth'];
    $datebirth = htmlspecialchars(strip_tags(trim($datebirth)), ENT_QUOTES, 'UTF-8');
    $address = $_POST['address'];
    $address = mb_convert_case($address, MB_CASE_TITLE, "UTF-8");
    $address = htmlspecialchars(strip_tags(trim($address)), ENT_QUOTES, 'UTF-8');
    $maritalstat = $_POST['maritalstat'];
    $maritalstat = htmlspecialchars(strip_tags(trim($maritalstat)), ENT_QUOTES, 'UTF-8');
    $religion = $_POST['religion'];
    $religion = htmlspecialchars(strip_tags(trim($religion)), ENT_QUOTES, 'UTF-8');
    $occupation = $_POST['occupation'];
    $occupation = htmlspecialchars(strip_tags(trim($occupation)), ENT_QUOTES, 'UTF-8');
    $religion = $_POST['religion'];
    $religion = htmlspecialchars(strip_tags(trim($religion)), ENT_QUOTES, 'UTF-8');
    $company = $_POST['company'];
    $company = htmlspecialchars(strip_tags(trim($company)), ENT_QUOTES, 'UTF-8');
    $fb = $_POST['fb'];
    $fb = htmlspecialchars(strip_tags(trim($fb)), ENT_QUOTES, 'UTF-8');
    $ig = $_POST['ig'];
    $ig = htmlspecialchars(strip_tags(trim($ig)), ENT_QUOTES, 'UTF-8');
    $allergy = $_POST['allergy'];
    $allergy = htmlspecialchars(strip_tags(trim($allergy)), ENT_QUOTES, 'UTF-8');

    if ($religion === 'Lain-lain') {
        $religion = $_POST['otherReligion']; // Use the value from the text input
    }

    if ($occupation === 'Lain-lain') {
        $occupation = $_POST['otherOccupation']; // Use the value from the text input
    }
    // Set timezone to Kuala Lumpur
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDateTime = date('Y-m-d H:i:s');

    $select_user = $conn->prepare("SELECT * FROM `clients` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Emel telah digunakan!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Pengesahan kata laluan tidak sepadan!';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `clients`(name,ic, email, password,phoneno,datebirth,address,maritalstat,religion,occupation,company,fb,ig,allergy,datetimeregistered) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $insert_user->execute([$name, $ic, $email, $cpass, $phoneno, $datebirth, $address, $maritalstat, $religion, $occupation, $company, $fb, $ig, $allergy, $currentDateTime]);
            $message[] = 'Pengguna sudah berjaya didaftarkan';
        }
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_user = $conn->prepare("DELETE FROM `clients` WHERE id = ?");
    $delete_user->execute([$delete_id]);
    // $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    // $delete_orders->execute([$delete_id]);
    // $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
    // $delete_messages->execute([$delete_id]);
    // $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    // $delete_cart->execute([$delete_id]);
    // $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    // $delete_wishlist->execute([$delete_id]);
    header('location:client-accounts.php');
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



<section class="add-any">

    <h1 class="heading">Daftar Pengguna</h1>

    <form action="" method="post">
        <div class="flex">
            <div class="inputBox">
                <label for="name">Nama Penuh :<span style="color: red;"></span></label>
                <input type="text" id="name" name="name" required placeholder="Masukkan nama penuh anda" maxlength="70" class="box">
            </div>

            <div class="inputBox">
                <label for="email">Emel :<span style="color: red;"></span></label>
                <input type="email" id="email" name="email" required placeholder="Masukkan emel" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            </div>
            <div class="inputBox ">
                <label for="passLogin">Kata Laluan:<span style="color: red;"></span></label>
                <div class="password-container">
                    <input type="password" id="passLogin" name="pass" required placeholder="Masukkan kata laluan " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                    <i style="font-size:160%;" id="togglePassword" class="fas fa-eye"></i>
                </div>
            </div>
            <div class="inputBox ">
                <label for="passLoginx">Kata Laluan Pengesahan:<span style="color: red;"></span></label>
                <div class="password-container">
                    <input type="password" id="passLoginx" name="cpass" required placeholder="Masukkan kata laluan pengesahan " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                    <i style="font-size:160%;" id="togglePasswordx" class="fas fa-eye"></i>
                </div>
            </div>
            <div class="inputBox">
                <label for="ic">Nombor Kad Pengenalan:<span style="color: red;"></span></label>
                <input type="text" id="ic" name="ic" required placeholder="ex : 020714104356 (tak perlu guna ' - ')" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
                <label for="phoneno">Nombor Telefon:<span style="color: red;"></span></label>
                <input type="text" id="phoneno" name="phoneno" required placeholder="Masukkan nombor telefon" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
                <label for="datebirth">Tarikh Lahir:<span style="color: red;"></span></label>
                <input type="date" id="datebirth" name="datebirth" required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
                <label for="address">Alamat Kediaman:<span style="color: red;"></span></label>
                <input type="text" id="address" name="address" required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box">

            </div>
            <div class="inputBox">
                <label for="maritalstat">Status Perkahwinan:<span style="color: red;"></span></label>
                <select id="maritalstat" name="maritalstat" required class="box">
                    <option value="">--Masukkan Status Perkahwinan Anda--</option>
                    <option value="Bujang">Bujang</option>
                    <option value="Sudah berkahwin">Sudah Berkahwin</option>
                    <option value="Bercerai">Bercerai</option>
                    <option value="Janda">Janda</option>
                    <option value="Duda">Duda</option>
                    <option value="Bertunang">Bertunang</option>
                </select>
            </div>
            <div class="inputBox">
                <label for="religion">Agama:<span style="color: red;"></span></label>
                <select id="religion" name="religion" required class="box" onchange="showOtherField()">
                    <option value="">--Masukkan Agama Anda--</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristian">Kristian</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Sikh">Sikh</option>
                    <option value="Yahudi">Yahudi</option>
                    <option value="Tiada">Tiada</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>
                <!-- Initially hidden text input for specifying "Lain-lain" religion -->
                <input type="text" id="otherReligion" name="otherReligion" style="display:none;" placeholder="Masukkan agama lain-lain anda" class="box">

            </div>
            <div class="inputBox">

                <label for="occupation">Pekerjaan:<span style="color: red;"></span></label>
                <select id="occupation" name="occupation" required class="box" onchange="toggleOtherOccupationField()">
                    <option value="">--Pilih Jenis Pekerjaan Anda--</option>
                    <option value="TIdak Bekerja">Tidak Bekerja</option>
                    <option value="Pelajar">Pelajar</option>
                    <option value="Akauntan">Akauntan</option>
                    <option value="Pembantu Tadbir">Pembantu Tadbir</option>
                    <option value="Arkitek">Arkitek</option>
                    <option value="Artis">Artis</option>
                    <option value="Peguam">Peguam</option>
                    <option value="Jurubank">Jurubank</option>
                    <option value="Bartender">Bartender</option>
                    <option value="Chef/Masak">Chef/Masak</option>
                    <option value="Jurutera Awam">Jurutera Awam</option>
                    <option value="Pemprogram Komputer">Pemprogram Komputer</option>
                    <option value="Pekerja Pembinaan">Pekerja Pembinaan</option>
                    <option value="Perunding">Perunding</option>
                    <option value="Wakil Perkhidmatan Pelanggan">Wakil Perkhidmatan Pelanggan</option>
                    <option value="Doktor Gigi">Doktor Gigi</option>
                    <option value="Pereka Bentuk">Pereka Bentuk (cth., Grafik, Fesyen, Dalaman)</option>
                    <option value="Doktor">Doktor</option>
                    <option value="Juruelektrik">Juruelektrik</option>
                    <option value="Jurutera">Jurutera (pelbagai bidang)</option>
                    <option value="Petani">Petani/Petani</option>
                    <option value="Penganalisis Kewangan">Penganalisis Kewangan</option>
                    <option value="Anggota Bomba">Anggota Bomba</option>
                    <option value="Pereka Grafik">Pereka Grafik</option>
                    <option value="Pendandan Rambut/Penggaya">Pendandan Rambut/Penggaya</option>
                    <option value="Pakar Sumber Manusia">Pakar Sumber Manusia</option>
                    <option value="Pakar Teknologi Maklumat">Pakar Teknologi Maklumat</option>
                    <option value="Wartawan">Wartawan</option>
                    <option value="Pustakawan">Pustakawan</option>
                    <option value="Pakar Pemasaran">Pakar Pemasaran</option>
                    <option value="Mekanik">Mekanik</option>
                    <option value="Jururawat">Jururawat</option>
                    <option value="Ahli Farmasi">Ahli Farmasi</option>
                    <option value="Juru Gambar">Juru Gambar</option>
                    <option value="Juruterbang">Juruterbang</option>
                    <option value="Tukang Paip">Tukang Paip</option>
                    <option value="Pegawai Polis">Pegawai Polis</option>
                    <option value="Profesor/Guru/Pendidik">Profesor/Guru/Pendidik</option>
                    <option value="Agen Hartanah">Agen Hartanah</option>
                    <option value="Penyambut Tetamu">Penyambut Tetamu</option>
                    <option value="Pekerja Runcit">Pekerja Runcit</option>
                    <option value="Jurujual">Jurujual</option>
                    <option value="Saintis">Saintis</option>
                    <option value="Pekerja Sosial">Pekerja Sosial</option>
                    <option value="Pembangun Perisian">Pembangun Perisian</option>
                    <option value="Cikgu">Cikgu</option>
                    <option value="Penterjemah">Penterjemah</option>
                    <option value="Pemandu Trak">Pemandu Trak</option>
                    <option value="Doktor Haiwan">Doktor Haiwan</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>

                <input type="text" id="otherOccupation" name="otherOccupation" style="display:none;" w placeholder="Masukkan pekerjaan anda" class="box">

            </div>
            <div class="inputBox">
                <label for="company">Nama Syarikat : <span style="color: gray;">Jika ada*</span></label>
                <input type="text" id="company" name="company" placeholder="Masukkan nama syarikat anda " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
                <label for="fb">Link Facebook : <span style="color: gray;">Jika ada*</span></label>
                <input type="text" id="fb" name="fb" placeholder="Masukkan link akaun facebook anda " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
                <label for="ig">Link Instagram : <span style="color: gray;">Jika ada*</span></label>
                <input type="text" id="ig" name="ig" placeholder="Masukkan link akaun instagram anda " maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">

            </div>
            <div class="inputBox">
            <label for="allergy">Alahan : <span style="color: gray;">Jika ada*</span></label>
    <input type="text" id="allergy" name="allergy"  placeholder="Nyatakan Alahan anda" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
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
                        <h1 class="display-4">Senarai pengguna</h1>

                        <div class="col-sm-12 table-responsive">
                            <table id="table" class="table table-striped table-bordered">
                                <thead>
                                  
                                    <th>ID </th>
                                    <th>Nama </th>
                                    <th>I/C</th>
                                    <th>Emel</th>
                                    <th>No.Tel</th>
                                    <th>Tarikh Lahir</th>
                                    <th>Alamat</th>
                                    <th>Status Perkahwinan </th>
                                    <th>Agama</th>
                                    <th>Pekerjaan</th>
                                    <th>Syarikat</th>
                                    <th>FBN </th>
                                    <th>IGN</th>
                                    <th>Alahan</th>
                                    <th>Tindakan</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $select_accounts = $conn->prepare("SELECT * FROM `clients`");
                                    $select_accounts->execute();
                                    if ($select_accounts->rowCount() > 0) {
                                        while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {


                                            $id = $row['id'];
                                            $name = $row['name'];
                                            $ic = $row['ic'];
                                            $email = $row['email'];
                                            $phoneno = $row['phoneno'];
                                            $datebirth = $row['datebirth'];
                                            $address = $row['address'];
                                            $maritalstat = $row['maritalstat'];
                                            $religion = $row['religion'];
                                            $occupation = $row['occupation'];
                                            $company = $row['company'];
                                            $fb = $row['fb'];
                                            $ig = $row['ig'];
                                            $allergy = $row['allergy'];
                                            $datetimeregistered = $row['datetimeregistered'];




                                            echo '<tr>
                							
                							<td>' . $id . '</td>
                							<td>' . $name . '</td>
                							<td>' . $ic . '</td>
                                     <td>' . $email . '</td>
                							<td>' . $phoneno . '</td>
                							<td>' . $datebirth . '</td>
                							<td>' . $address . '</td>
                                     <td>' . $maritalstat . '</td>
                							<td>' . $religion . '</td>
                							<td>' . $occupation . '</td>
                							<td>' . $company . '</td>
                                     <td>' . $fb . '</td>
                							<td>' . $ig . '</td>
                							<td>' . $allergy . '</td>
											
                                     <td>
            <a href="client-accounts.php?delete=' . $id . '" onclick="return confirm(\'Adakah anda pasti untuk buang akaun ini? Maklumat berkaitan pengguna ini akan dibuang!\');" class="tblbtn tblremove"><i style="margin: 0em 0.2em;"class="fa fa-ban"></i>Delete</a>

        
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