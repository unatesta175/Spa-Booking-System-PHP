<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user-login.php');
};

// Fetch user data from database
$user_data = [];
if(!empty($user_id)){
   $select_user = $conn->prepare("SELECT * FROM `clients` WHERE id = ?");
   $select_user->execute([$user_id]);
   if($select_user->rowCount() > 0){
      $user_data = $select_user->fetch(PDO::FETCH_ASSOC);
   }
}

// Check if user data exists
if(empty($user_data)){
   header('location:user-login.php');
   exit();
}

if(isset($_POST['order'])){

   // Use data from database
   $name = $user_data['name'];
   $number = $user_data['phoneno'];
   $email = $user_data['email'];
   $address = $user_data['address'];
   
   $method = $_POST['method'];
   $method = htmlspecialchars(strip_tags(trim($method)), ENT_QUOTES, 'UTF-8');
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $success_message = 'Order berjaya dibuat!';
   }else{
      $error_message = 'Troli anda kosong!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
   <!-- Sweet Alert -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
   
<?php include 'components/user-header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>order anda</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= 'RM'.$fetch_cart['price'].' x '. $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         }else{
            echo '<p class="empty">troli anda kosong!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
         <div class="grand-total">Jumlah Harga : <span>RM <?= $grand_total; ?></span></div>
      </div>

      <h3>Maklumat order anda</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nama anda :</span>
            <input type="text" name="name" placeholder="Masukkan nama anda" class="box" maxlength="100" value="<?= isset($user_data['name']) ? htmlspecialchars($user_data['name']) : ''; ?>" required>
         </div>
         <div class="inputBox">
            <span>Nombor telefon :</span>
            <input type="text" name="number" placeholder="Masukkan nombor telefon" class="box" maxlength="15" value="<?= isset($user_data['phoneno']) ? htmlspecialchars($user_data['phoneno']) : ''; ?>" required>
         </div>
         <div class="inputBox">
            <span>Email anda :</span>
            <input type="email" name="email" placeholder="Masukkan email anda" class="box" maxlength="100" value="<?= isset($user_data['email']) ? htmlspecialchars($user_data['email']) : ''; ?>" required>
         </div>
         <div class="inputBox">
            <span>Kaedah pembayaran :</span>
            <select name="method" class="box" required>
               <option value="">--Pilih kaedah pembayaran--</option>
               <option value="Tunai">Tunai</option>
               <option value="Kad kredit">Kad Kredit</option>
               <option value="Kad debit">Kad Debit</option>
               <option value="Online Banking">Online Banking</option>
               <option value="E-Wallet">E-Wallet</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Alamat penuh :</span>
            <textarea name="flat" placeholder="Masukkan alamat penuh anda" class="box" maxlength="500" rows="3" required><?= isset($user_data['address']) ? htmlspecialchars($user_data['address']) : ''; ?></textarea>
         </div>
         <div class="inputBox">
            <span>Bandar :</span>
            <input type="text" name="city" placeholder="Contoh: Kuala Lumpur" class="box" maxlength="100" required>
         </div>
         <div class="inputBox">
            <span>Negeri :</span>
            <select name="state" class="box" required>
               <option value="">--Pilih negeri--</option>
               <option value="Johor">Johor</option>
               <option value="Kedah">Kedah</option>
               <option value="Kelantan">Kelantan</option>
               <option value="Melaka">Melaka</option>
               <option value="Negeri Sembilan">Negeri Sembilan</option>
               <option value="Pahang">Pahang</option>
               <option value="Pulau Pinang">Pulau Pinang</option>
               <option value="Perak">Perak</option>
               <option value="Perlis">Perlis</option>
               <option value="Sabah">Sabah</option>
               <option value="Sarawak">Sarawak</option>
               <option value="Selangor">Selangor</option>
               <option value="Terengganu">Terengganu</option>
               <option value="Wilayah Persekutuan Kuala Lumpur">WP Kuala Lumpur</option>
               <option value="Wilayah Persekutuan Labuan">WP Labuan</option>
               <option value="Wilayah Persekutuan Putrajaya">WP Putrajaya</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Poskod :</span>
            <input type="text" name="pin_code" placeholder="Contoh: 50000" maxlength="5" class="box" pattern="[0-9]{5}" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>













<?php include 'components/footer.php'; ?>

<?php if (isset($success_message)): ?>
<script>
   Swal.fire({
      title: 'Berjaya!',
      text: '<?= $success_message ?>',
      icon: 'success',
      confirmButtonText: 'OK'
   }).then((result) => {
      if (result.isConfirmed) {
         window.location.href = 'orders.php';
      }
   });
</script>
<?php endif; ?>

<?php if (isset($error_message)): ?>
<script>
   Swal.fire({
      title: 'Tidak berjaya!',
      text: '<?= $error_message ?>',
      icon: 'error',
      confirmButtonText: 'OK'
   });
</script>
<?php endif; ?>

<script src="js/script.js"></script>

</body>
</html>