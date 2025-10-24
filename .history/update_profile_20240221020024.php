<?php
ob_start();
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   
} else {
   $user_id = '';

}
;

if (isset($_POST['submit'])) {



   $name = $_POST['name'];
   $email = $_POST['email'];
   $ic = $_POST['ic'];
   $studno = $_POST['studno'];
   $program_id = $_POST['program_id'];
   $faculty_id = $_POST['faculty_id'];
   $part = $_POST['part'];
   $homephoneno = $_POST['homephoneno'];
   $phoneno = $_POST['phoneno'];


   $name = $_POST['name'];
   $ic = $_POST['ic'];
   $email = $_POST['email'];
   
  

   $phoneno = $_POST['phoneno'];
   $datebirth = $_POST['datebirth'];
   $address = $_POST['address'];
   $maritalstat = $_POST['maritalstat'];
   $religion = $_POST['religion'];

   $occupation = $_POST['occupation'];
   $religion = $_POST['religion'];
   $company = $_POST['company'];
   $fb = $_POST['fb'];
   $ig = $_POST['ig'];

   $allergy = $_POST['allergy'];

   if ($religion === 'Lain-lain') {
      $religion = $_POST['otherReligion']; // Use the value from the text input
    } 

    if ($occupation === 'Lain-lain') {
      $occupation = $_POST['otherOccupation']; // Use the value from the text input
    } 
     // Set timezone to Kuala Lumpur
     date_default_timezone_set('Asia/Kuala_Lumpur');
     $currentDateTime = date('Y-m-d H:i:s');

   $update = $conn->prepare("UPDATE `clients` SET name = ?, ic = ?, email = ?, phoneno = ? , datebirth= ? , address= ? , maritalstat= ?, religion= ?, occupation= ?, company= ?, fb= ?, ig= ?, allergy= ? WHERE id = ?");
   $update->execute([$name, $ic,$email, $phoneno, $datebirth, $address, $maritalstat,$religion,$occupation,$company,$fb,$ig, $allergy, $user_id]);

}

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
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>


   <br>
   <?php
   if (!isset($_SESSION['user_id'])) {
      header('location:user_login.php');
   } else {

      ?>
      <section class="form-container">

         <form action="" method="post">

            <?php

            $result = $conn->prepare("SELECT * FROM `clients` WHERE id=?");
            $result->execute([$user_id]);



            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

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
               }
            }
            ?>

            <h3>Kemaskini Profil</h3>
            <br>
            <br>
            <label  for="name">Nama Penuh :</label>
            <input type="text" name="name" required placeholder="Masukkan nama penuh anda" maxlength="100" class="box"
               value="<?php echo $name; ?>">

            <label  for="name">Emel :</label>
            <input type="text" name="email" required placeholder="Masukkan emel anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $email; ?>>

            <label  for="name">No. Kad Pengenalan :</label>
            <input type="text" name="ic" required placeholder="Masukkan no. kad pengenalan anda" maxlength="100"
               oninput="this.value = this.value.replace(/\s/g, '')" class="box" value=<?php echo $ic; ?>>

            <label  for="name">No. Telefon :</label>
            <input type="text" name="phoneno" required placeholder="Masukkan no. telefon anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $phoneno; ?>>
           
               <label  for="name">Tarikh Lahir :</label>
            <input type="date" name="datebirth" required placeholder="Masukkan tarikh lahir anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $datebirth; ?>>
           
               <label  for="name">No. Telefon :</label>
            <input type="text" name="studno" required placeholder="Masukkan no. telefon anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $phoneno; ?>>

            
    <label for="address">Alamat Kediaman:<span style="color: red;">*</span></label>
    <input type="text" id="address" name="address"  required placeholder="Masukkan alamat kediaman anda" maxlength="70" class="box" >
   
    <label for="maritalstat">Status Perkahwinan:<span style="color: red;">*</span></label>
    <select id="maritalstat" name="maritalstat" required class="box">
    <option value="<?php echo $maritalstat; ?>" selected hidden><?php echo $maritalstat; ?></option>
    <option value="Bujang">Bujang</option>
    <option value="Sudah berkahwin">Sudah Berkahwin</option>
    <option value="Bercerai">Bercerai</option>
    <option value="Janda">Janda</option>
    <option value="Duda">Duda</option>
    <option value="Bertunang">Bertunang</option>
    </select>
    
    <label for="religion">Agama:<span style="color: red;">*</span></label>
<select id="religion" name="religion" required class="box" onchange="showOtherField()">
<option value="<?php echo $religion; ?>" selected hidden><?php echo $religion; ?></option>
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
    <input value=<?php echo $company; ?>type="text" id="company" name="company" placeholder="Masukkan nama syarikat anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="fb">Facebook Username:</label>
    <input value=<?php echo $phoneno; ?>type="text" id="fb" name="fb" placeholder="Masukkan username facebook anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    
    <label for="ig">Instagram Username:</label>
    <input type="text" id="ig" name="ig" placeholder="Masukkan username instagram anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
   
    <label for="allergy">Alahan:<span style="color: red;">*</span> Tulis 'tiada' jika tiada alahan</label>
    <input type="text" id="allergy" name="allergy"  required placeholder="Nyatakan Alahan anda jika ada" maxlength="70" class="box" oninput="this.value = this.value.replace(/\s/g, '')">




          


            <input type="submit" style="border-radius:15px; text-transform:uppercase;" value="Kemaskini" class="btn"
               name="submit">

         </form>
      </section>"
      <?php
   }
   ?>
   <br>












   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>