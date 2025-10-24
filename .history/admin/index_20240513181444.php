<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Submit form without reloading page</title>

            <!-- CSS Files Start Here-->
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
                    
            <!-- CSS Files End Here-->

                <link  href="https://fonts.googleapis.com/css2?family=ABeeZee&family=Acme&family=Balsamiq+Sans&family=Bowlby+One+SC&family=Fredoka+One&family=Josefin+Sans:wght@700&family=Lobster&display=swap" rel="stylesheet">
          

            <!-- Script Files Start Here--> 
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
            <!-- Script Files Ends Here-->    
            <style>
            
            </style>
</head>
<body  >

<div align="center" class="container py-5 my-5 col-5">

    <form id="registration_form" method="post"  action="javascript:void(0)">
    <div >
                        <label class="control-label col-sm-3 col-sm-offset-2">Client: </label>
                        <select name="client" class="form-control" id="client">
                            <option>---Select---</option>
                            <?php


                            $select_accounts = $conn->prepare("SELECT * FROM `clients`");
                            $select_accounts->execute();
                            if ($select_accounts->rowCount() > 0) {
                                while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>
                    <label class="control-label col-sm-3 col-sm-offset-2" >Date: </label>
                    <div class="col-sm-2">
                        <input type="date" name="date" class="form-control" id="date">
                    </div>
                    <div class="col-sm-2">
                    <label class="control-label col-sm-3 col-sm-offset-2" >Therapist: </label>
                        <select name="staff" class="form-control" id="staff">
                            <option>---Select---</option>
                            <?php


                            $select_accounts = $conn->prepare("SELECT * FROM `staffs`");
                            $select_accounts->execute();
                            if ($select_accounts->rowCount() > 0) {
                                while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                }
                            }

                            ?>
                        </select>

                    </div>

                    <div class="col-sm-2">
                    <label class="control-label col-sm-3 col-sm-offset-2" >Service: </label>
                        <select name="service" class="form-control" id="service">
                            <option>---Select---</option>
                            <?php
                            $select_accounts = $conn->prepare("SELECT * FROM `services`");
                            $select_accounts->execute();
                            if ($select_accounts->rowCount() > 0) {
                                while ($row = $select_accounts->fetch(PDO::FETCH_ASSOC)) {

                                    echo "<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
                                }
                            }


                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">

                    <label class="control-label col-sm-3 col-sm-offset-2" >Duration: </label>
                        <input readonly  class="form-control" type="text" name="duration" id="duration">
                    </div>
                    <div class="row" id='test'></div>
            </div>
                    <input type="text"  name="name"  placeholder="Name" > <br><br>
                    <input type="email"  name="email"  placeholder="E-mail" required="required"><br><br>
     
                    <button type="submit" id="Submit"  class="btn btn-purple" >Submit</button>
    
    <div id="messages"></div>
    </form> 

</div>
	
	 
	 
	<script >//SENDING DATA BY AJAX
$(document).ready(function () {
  $("#Submit").click(function (e) {
    e.preventDefault();

    var email = $("input[name=email]").val();
    var name = $("input[name=name]").val();

    if (email == "") {
      $("#messages").html('<p style="color:red;">Email id Required! </p>');

      setTimeout(function () {
        $("#messages").html("");
      }, 1000);
    } else {
      /* Submit form data using ajax*/

      $.ajax({
        url: "backend.php",
        method: "post",
        data: $("#registration_form").serialize(),

        beforeSend: function () {
          $("#messages").html(
            '<br><span class="spinner-border fast"  style="width: 2rem; height: 2rem;color:green;"  role="status"></span>'
          );
        },

        success: function (Response) {
          $("#messages").html(Response);
          $("#registration_form")[0].reset();

          setTimeout(function () {
            $("#messages").html("");
          }, 20000);
        },
      });
    }
  });
});
</script>
</body>
</html>