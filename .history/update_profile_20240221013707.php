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
   
   $pass = ($_POST['pass']);
   $cpass = ($_POST['cpass']);
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



   $update = $conn->prepare("UPDATE `clients` SET name = ?, ic = ?, email = ?, password = ?, phoneno = ? ,faculty_id = ? , datebirth= ? , address= ? , maritalstat= ?, religion= ?, occupation= ?, company= ?WHERE student_id = ?");
   $update->execute([$name,$email, $ic, $studno, $program_id, $faculty_id, $part, $phoneno, $homephoneno, $user_id]);

   $insert_user = $conn->prepare("INSERT INTO `clients`(name,ic, email, password,phoneno,datebirth,address,maritalstat,religion,occupation,company,fb,ig,allergy,datetimeregistered) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
   $insert_user->execute([$name,$ic,$email,$cpass,$phoneno,$datebirth,$address,$maritalstat,$religion,$occupation,$company,$fb,$ig, $allergy, $currentDateTime]);



}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>eleas.uitm.edu.my</title>
   <link rel="website icon" type="png" href="images/logoS.png">

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

            $result = $conn->prepare("SELECT * FROM `students` WHERE student_id=?");
            $result->execute([$user_id]);



            if ($result->rowCount() > 0) {
               while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                  $name = $row['name'];
                  $email = $row['email'];
                  $ic = $row['ic'];
                  $studno = $row['student_no'];
                  $program_id = $row['program_id'];
                  $faculty_id = $row['faculty_id'];
                  $part = $row['semester'];
                  $phoneno = $row['phone_no'];
                  $homephoneno = $row['home_phone_no'];

                  $result2 = $conn->prepare("SELECT * FROM `programs` WHERE program_id=?");
                  $result2->execute([$program_id]);
                  if ($result2->rowCount() > 0) {
                     while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {

                        $prog_name = $row2['program_name'];
                       
                        $program_code = $row2['program_code'];
                        

                     }
                  }

                  $result2 = $conn->prepare("SELECT * FROM `faculty` WHERE faculty_id=?");
                  $result2->execute([$faculty_id ]);
                  if ($result2->rowCount() > 0) {
                     while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {

                       
                        $faculty_name = $row2['name'];
                        $faculty_code = $row2['faculty_code'];
                        

                     }
                  }



               }
            }
            ?>

            <h3>Kemaskini Profil</h3>
            <br>
            <br>
            <label class="bigger" style="display: flex;flex-direction: row;" for="name">Nama Penuh :</label>
            <input type="text" name="name" required placeholder="Isi nama penuh anda" maxlength="100" class="box"
               value="<?php echo $name; ?>">

               <label class="bigger" style="display: flex;flex-direction: row;" for="name">Emel :</label>
            <input type="text" name="email" required placeholder="Isi emel anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $email; ?>>



            

            <label class="bigger" style="display: flex;flex-direction: row;" for="name">No. Kad Pengenalan :</label>
            <input type="text" name="ic" required placeholder="Isi no. kad pengenalan anda" maxlength="100"
               oninput="this.value = this.value.replace(/\s/g, '')" class="box" value=<?php echo $ic; ?>>

            <label class="bigger" style="display: flex;flex-direction: row;" for="name">No. Matrik Pelajar :</label>
            <input type="text" name="studno" required placeholder="Isi no. matrik pelajar anda" maxlength="70" class="box"
               oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $studno; ?>>

            <label class="bigger" style="display: flex;flex-direction: row;" for="name">Kod Program :</label>
            <select name="program_id" class="box" required placeholder="Isi kod program anda">
               <option value="<?php echo $program_id; ?>" selected hidden><?php echo $program_code . ' - ' . $prog_name; ?>
               </option>


               <?php

               $result = $conn->prepare("SELECT * FROM `programs`");
               $result->execute();
               if ($result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                     <option value="<?php echo $row['program_id']; ?>"><?php echo $row['program_code'] . " - " . $row['program_name']; ?></option>
                  <?php }
               } ?>
            </select>

            <label class="bigger" style="display: flex;flex-direction: row;" for="name">Fakulti :</label>
       
            <select name="faculty_id" class="box" required placeholder="Isi fakulti anda">
               <option value="<?php echo $faculty_id; ?>" selected hidden><?php echo $faculty_code . ' - ' . $faculty_name; ?>
               </option>


               <?php

               $result = $conn->prepare("SELECT * FROM `faculty`");
               $result->execute();
               if ($result->rowCount() > 0) {
                  while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                     <option value="<?php echo $row['faculty_id']; ?>"><?php echo $row['faculty_code'] . " - " . $row['name']; ?></option>
                  <?php }
               } ?>
            </select>
        



            <!-- <input type="text" name="program_code" required placeholder="enter your course code" maxlength="70" class="box"
            oninput="this.value = this.value.replace(/\s/g, '')"> -->
            <!-- <input type="text" name="faculty" required placeholder="enter your faculty name" maxlength="70" class="box">   -->


            <label class="bigger" style="display: flex;flex-direction: row;" for="name">Semester :</label>
            <input type="number" name="part" required placeholder="enter your current semester" maxlength="70" class="box"
               value=<?php echo $part; ?>>

            <label class="bigger" style="display: flex;flex-direction: row;" for="name">No. Telefon :</label>
            <input type="tel" name="phoneno" min="0" max="9999999999" placeholder="enter your phone number" required
               class="box" oninput="this.value = this.value.replace(/\s/g, '')" value=<?php echo $phoneno; ?>>
            <label class="bigger" style="display: flex;flex-direction: row;" for="name">No. Telefon Rumah :</label>
            <input type="tel" name="homephoneno" min="0" max="9999999999" placeholder="enter your home phone number"
               oninput=" this.value=this.value.replace(/\s/g, '' )"  class="box" value=<?php echo $homephoneno; ?>>
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