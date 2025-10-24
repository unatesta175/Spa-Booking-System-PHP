<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Stepper Example</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>
<section>
<div class="container mt-5 p-3"
         style="background-color: var(--white);box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); border-radius:15px; ">
         <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0"
               aria-valuemax="100"></div>
         </div>

         <form id="step1">
            <h2>Pilih tarikh dan masa sesi terapi</h2>
            <div class="form-group">
               <label for="input1">Input 1</label>
               <input type="text" class="form-control" id="input1" placeholder="Enter Input 1">
            </div>
            <button type="button" class="btns btn-primarys next">Next</button>
         </form>

         <form id="step2" style="display: none;">
            <h2>Step 2</h2>
            <div class="form-group">
               <label for="input2">Input 2</label>
               <input type="text" class="form-control" id="input2" placeholder="Enter Input 2">
            </div>
            <button type="button" class="btns btn-secondarys prev">Previous</button>
            <button type="button" class="btns btn-primarys next">Next</button>
         </form>

         <form id="step3" style="display: none;">
            <h2>Step 3</h2>
            <div class="form-group">
               <label for="input3">Input 3</label>
               <input type="text" class="form-control" id="input3" placeholder="Enter Input 3">
            </div>
            <button type="button" class="btns btn-secondarys prev">Previous</button>
            <button type="submit" class="btns btn-success">Submit</button>
         </form>
      </div>

</section>

<!-- jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
      $(document).ready(function () {
         var currentStep = 1;

         $('.next').click(function () {
            if (currentStep < 3) {
               $('#step' + currentStep).hide();
               currentStep++;
               $('#step' + currentStep).show();
               updateProgressBar();
            }
         });

         $('.prev').click(function () {
            if (currentStep > 1) {
               $('#step' + currentStep).hide();
               currentStep--;
               $('#step' + currentStep).show();
               updateProgressBar();
            }
         });

         function updateProgressBar() {
            var progress = (currentStep - 1) * 50;
            $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
         }
      });
   </script>

</body>
</html>
