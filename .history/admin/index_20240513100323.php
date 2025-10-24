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
                /* #registration_form
                {
                  
                    background: rgb(17, 2, 23);
                    padding:30px;
                    color: rgb(90, 7, 122);
                }
                #registration_form:hover
                {
                    box-shadow: 1px 1px 2px black, 0 0 25px rgb(90, 7, 122), 0 0 5px darkblue;
                }
              
 
            .btn-purple
            {
                border-width: 2px;
            border-style: inset ;
            border-color: #5a077a;
            color:white;
            }
            .btn-purple:hover {
                color:white;
                box-shadow: 1px 1px 2px black, 0 0 25px rgb(90, 7, 122), 0 0 5px #4f4cff;
            } */
 
 
            </style>
</head>
<body  >

<div align="center" class="container py-5 my-5 col-5">

    <form id="registration_form" method="post"  action="javascript:void(0)">
    <h1 >Subscribe Now ! </h1>
                    <input type="text" hidden  name="name"  placeholder="Name" > <br><br>
                    <input type="email" hidden value="tiber@gmail.com" name="email"  placeholder="E-mail" required="required"><br><br>
     
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