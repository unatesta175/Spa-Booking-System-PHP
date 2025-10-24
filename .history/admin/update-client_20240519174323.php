<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin-login.php');
}

if (isset($_POST['update'])) {

   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $username = $_POST['username'];
   $username = filter_var($username, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   date_default_timezone_set('Asia/Kuala_Lumpur');
   $currentDateTime = date('Y-m-d H:i:s');

   $update_product = $conn->prepare("UPDATE `admins` SET name = ?, username = ?, email = ?, updated_at = ? WHERE id = ?");
   $update_product->execute([$name, $username, $email, $currentDateTime, $pid]);

   // $message[] = 'Admin Berjaya Dikemaskini!';
   $_SESSION['delete'] = true;
}
if (isset($_SESSION['delete'])) {
   // Prepare JavaScript for showing the Sweet Alert
   unset($_SESSION['delete']);
   $bookdeleteScript = "<script>
          window.onload = function() {
              Swal.fire({
                  title: 'Berjaya!',
                  text: 'Berjaya dikemaskini!',
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

</head>

<body>
   <?php echo $bookdeleteScript; ?>
   <?php include '../components/admin_header.php'; ?>

   <section class="update-product">

      <h1 class="heading">Kemaskini Pengguna</h1>

      <?php

      $update_id = isset($_GET['update']) ? $_GET['update'] : '';

      $select_products = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
      $select_products->execute([$update_id]);
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Decode the JSON to an array

            $name
      ?>
            <form action="" method="post" enctype="multipart/form-data">


               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

               <label  for="name">Nama Penuh :</label>
            <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box"
               value="<?php echo $fetch_products['name']; ?>">
<!-- 
            <label  for="name">Emel :</label>
            <input type="text" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $email; ?>"> -->

            <label  for="name">No. Kad Pengenalan :</label>
            <input type="text" name="ic" required placeholder="Masukkan no. kad pengenalan anda" maxlength="100"
           oninput="this.value = this.value.replace(/\s/g, '')" class="box" value="<?php echo $fetch_products['ic']; ?>">

            <label  for="name">No. Telefon :</label>
            <input type="text" name="phoneno" required placeholder="Masukkan no. telefon anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch_products['phoneno']; ?>">
           
               <label  for="name">Tarikh Lahir :</label>
            <input type="date" name="datebirth" required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo $fetch_products['datebirth']; ?>">
           
    <label for="address">Alamat Kediaman:<span style="color: red;">*</span></label>
    <input value="<?php echo $fetch_products['address']; ?>" type="text" id="address" name="address"  required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box" >
   
    <label for="maritalstat">Status Perkahwinan:<span style="color: red;">*</span></label>
    <select id="maritalstat" name="maritalstat" required class="box">
    <option value="<?php echo $fetch_products['maritalstat']; ?>" selected hidden><?php echo $fetch_products['maritalstat']; ?></option>
    <option value="Bujang">Bujang</option>
    <option value="Sudah berkahwin">Sudah Berkahwin</option>
    <option value="Bercerai">Bercerai</option>
    <option value="Janda">Janda</option>
    <option value="Duda">Duda</option>
    <option value="Bertunang">Bertunang</option>
    </select>
    
    <label for="religion">Agama:<span style="color: red;">*</span></label>
<select id="religion" name="religion" required class="box" onchange="showOtherField()">
<option value="<?php echo $religion; ?>" selected hidden><?php echo $fetch_products['religion']; ?></option>
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


    
<label for="occupation">Pekerjaan:<span style="color: red;">*</span></label>
<select id="occupation" name="occupation" required class="box" onchange="toggleOtherOccupationField()">
<option value="<?php echo $occupation; ?>" selected hidden><?php echo $occupation; ?></option>
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

<input type="text" id="otherOccupation" name="otherOccupation" style="display:none;" placeholder="Masukkan pekerjaan anda" class="box">

    <label for="company">Nama Syarikat:</label>
    <input value="<?php echo $company; ?>" type="text" id="company" name="company" placeholder="Masukkan nama syarikat anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="fb">Facebook Username:</label>
    <input value="<?php echo $fb; ?>" type="text" id="fb" name="fb" placeholder="Masukkan username facebook anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="ig">Instagram Username:</label>
    <input value="<?php echo $ig; ?>" type="text" id="ig" name="ig" placeholder="Masukkan username instagram anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   
    <label for="allergy">Alahan:<span style="color: red;">*</span> Tulis 'tiada' jika tiada alahan</label>
    <input value="<?php echo $allergy; ?>" type="text" id="allergy" name="allergy"  required placeholder="Nyatakan Alahan anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">




               <div class="flex-btn">
                  <input type="submit" name="update" class="btn" value="Kemaskini">
                  <a href="client-accounts.php" class="option-btn">Kembali</a>
               </div>
            </form>

      <?php
         }
      }
      ?>

   </section>

   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="../js/admin_script.js"></script>

</body>

</html>