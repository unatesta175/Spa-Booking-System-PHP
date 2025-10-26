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

      <div class="display-orders" style="margin-bottom: 2rem;">
         <p><strong>Nama:</strong> <span><?= htmlspecialchars($user_data['name']); ?></span></p>
         <p><strong>Nombor Telefon:</strong> <span><?= htmlspecialchars($user_data['phoneno']); ?></span></p>
         <p><strong>Email:</strong> <span><?= htmlspecialchars($user_data['email']); ?></span></p>
         <p><strong>Alamat:</strong> <span><?= htmlspecialchars($user_data['address']); ?></span></p>
      </div>

      <div class="flex">
         <div class="inputBox">
            <span>Kaedah pembayaran : <span style="color: red;">*</span></span>
            <select name="method" class="box" required>
               <option value="">--Pilih kaedah pembayaran--</option>
               <option value="Tunai">Tunai</option>
               <option value="Kad kredit">Kad Kredit</option>
               <option value="Kad debit">Kad Debit</option>
               <option value="Online Banking">Online Banking</option>
               <option value="E-Wallet">E-Wallet</option>
            </select>
         </div>
      </div>

      <p style="margin-top: 1rem; font-size: 1.4rem; color: #666;">
         <i class="fas fa-info-circle"></i> Jika maklumat anda tidak tepat, sila kemaskini di halaman <a href="update-profile.php" style="color: var(--main-color);">Profil</a> anda.
      </p>

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