<?php

if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){
      header('location:user-login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = htmlspecialchars(strip_tags(trim($pid)), ENT_QUOTES, 'UTF-8');
      $name = $_POST['name'];
      $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
      $price = $_POST['price'];
      $price = htmlspecialchars(strip_tags(trim($price)), ENT_QUOTES, 'UTF-8');
      $image = $_POST['image'];
      $image = htmlspecialchars(strip_tags(trim($image)), ENT_QUOTES, 'UTF-8');

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$name, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'already added to wishlist!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
         $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
         $message[] = 'added to wishlist!';
      }

   }

}
if(isset($_POST['add_to_book'])){
header('location:booking.php?service_id='. $_POST['pid'].'&service_duration='. $_POST['duration']);
}
if(isset($_POST['add_to_cart'])){

   if($user_id == ''){
      header('location:user-login.php');
   }else{

      $pid = $_POST['pid'];
      $pid = htmlspecialchars(strip_tags(trim($pid)), ENT_QUOTES, 'UTF-8');
      $name = $_POST['name'];
      $name = htmlspecialchars(strip_tags(trim($name)), ENT_QUOTES, 'UTF-8');
      $price = $_POST['price'];
      $price = htmlspecialchars(strip_tags(trim($price)), ENT_QUOTES, 'UTF-8');
      $image = $_POST['image'];
      $image = htmlspecialchars(strip_tags(trim($image)), ENT_QUOTES, 'UTF-8');
      $qty = $_POST['qty'];
      $qty = htmlspecialchars(strip_tags(trim($qty)), ENT_QUOTES, 'UTF-8');

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'already added to cart!';
      }else{

         $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         $check_wishlist_numbers->execute([$name, $user_id]);

         if($check_wishlist_numbers->rowCount() > 0){
            $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
            $delete_wishlist->execute([$name, $user_id]);
         }

         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         $message[] = 'added to cart!';
         
      }

   }

}

?>