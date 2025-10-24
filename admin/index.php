<html>  
<head>  
    <title>Registration Form</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</head>
<style>
 .box
 {
  width:100%;
  max-width:600px;
  background-color:#f9f9f9;
  border:1px solid #ccc;
  border-radius:5px;
  padding:16px;
  margin:0 auto;
 }
 .error
{
  color: red;
  font-weight: 700;
} 
</style>
<body>  
    <div class="container">  
    <h2 class="text-center">Update Data with Modal using Jquery AJAX</h2>
    <br/>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#adduser">Add New User</button>
    <br/><br/>
    <table class="table table-bordered">
    <thead>
      <th>Id</th>
      <th>Name</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Address</th>
      <th>Action</th>
      </thead>
    <tbody id="result">
    </tbody>
   </table>
  </div>
 </body>  
</html>
<script type="text/javascript">
  $(document).ready(function(){
    showdata();
    getdata();
    updaterecord();
    $("#register").on("click", function(e){
    var name = $("#name").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var address = $("#address").val();
    $.ajax({
      url: 'insert-data.php',
      method: 'post',
      data: {fullname: name, emailid: email, phone: phone, address: address },
      success: function(data){
        $("#err").html(data);
        $("form").trigger('reset');
        showdata();
      }
    });

   });
  });

  function showdata(){
    $.ajax({
      url: 'show-data.php',
      method: 'post',
      success: function(result)
      {
        $("#result").html(result);
      }
    });
  }
  
  function getdata(){
    $(document).on("click", "#edit_btn", function(){
      var id = $(this).attr('data-id');
      // console.log(id);
      $.ajax({
        url: 'get-data.php',
        method: 'post',
        data: {userid: id},
        dataType: 'JSON',
        success: function(data)
        {
          $('#updateuser').modal('show');
          $('#userid').val(data.id);
          $('#edit_name').val(data.name);
          $('#edit_email').val(data.email);
          $('#edit_phone').val(data.phone);
          $('#edit_address').val(data.address);

        }
      });
    });
  }

 function updaterecord(){
  $(document).on("click","#update", function(){
     var id = $('#userid').val();
     var name = $('#edit_name').val();
     var email = $('#edit_email').val();
     var phone = $('#edit_phone').val();
     var address = $('#edit_address').val();
     $.ajax({
      url: 'update-data.php',
      method: 'post',
      data: {id: id, name: name, email: email, phone: phone, address: address},
      success: function(data){
        $('#update_err').html(data);
        showdata();
      }
     });
  });
 }


  $(document).on("click","#close_btn",function(e){
    $('#err').html('');
    $('#update_err').html('');
    });
</script>


<!--Add Modal -->
<div class="modal fade" id="adduser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="box">
      <form>
      <div class="form-group">
       <label for="name">Enter Your Name</label>
       <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control"/>
      </div>  
       <div class="form-group">
       <label for="email">Enter Your Email</label>
       <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control"/>
      </div>
      <div class="form-group">
       <label for="phone">Enter Your Phone Number</label>
       <input type="text" name="phone" id="phone" placeholder="Enter Phone Number" class="form-control"/>
      </div>
      <div class="form-group">
       <label for="phone">Enter Your Address</label>
       <textarea name="address" id="address" placeholder="Enter Address" required class="form-control"></textarea> 
      </div>
    </form>
      <div class="form-group">
       <input type="submit" id="register" name="register" value="Submit" class="btn btn-success" />
      <button type="button" class="btn btn-danger" data-dismiss="modal" id="close_btn">Close</button>
       </div>
       <p class="error" id="err"></p>
     </div>
      </div>
      </div>
  </div>
</div>

<!--Update Modal -->
<div class="modal fade" id="updateuser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="box">
      <form>
      <div class="form-group">
       <label for="name">Enter Your Name</label>
       <input type="text" name="name" id="edit_name" placeholder="Enter Name" class="form-control"/>
       <input type="hidden" id="userid">
      </div>  
       <div class="form-group">
       <label for="email">Enter Your Email</label>
       <input type="text" name="email" id="edit_email" placeholder="Enter Email" class="form-control"/>
      </div>
      <div class="form-group">
       <label for="phone">Enter Your Phone Number</label>
       <input type="text" name="phone" id="edit_phone" placeholder="Enter Phone Number" class="form-control"/>
      </div>
      <div class="form-group">
       <label for="phone">Enter Your Address</label>
       <textarea name="address" id="edit_address" placeholder="Enter Address" required class="form-control"></textarea> 
      </div>
    </form>
      <div class="form-group">
       <input type="submit" id="update" name="update" value="Update" class="btn btn-success" />
      <button type="button" class="btn btn-danger" data-dismiss="modal" id="close_btn">Close</button>
       </div>
       <p class="error" id="update_err"></p>
     </div>
      </div>
      </div>
  </div>
</div>




