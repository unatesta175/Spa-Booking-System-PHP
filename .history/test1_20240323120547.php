<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Stepper Example</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container mt-5" style="box-shadow: var(--box-shadow); border-radius:15px;">
  <div class="progress mb-4">
    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">Step 1</div>
  </div>

  <form id="step1">
    <h2>Step 1</h2>
    <div class="form-group">
      <label for="input1">Input 1</label>
      <input type="text" class="form-control" id="input1" placeholder="Enter Input 1">
    </div>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step2" style="display: none;">
    <h2>Step 2</h2>
    <div class="form-group">
      <label for="input2">Input 2</label>
      <input type="text" class="form-control" id="input2" placeholder="Enter Input 2">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step3" style="display: none;">
    <h2>Step 3</h2>
    <div class="form-group">
      <label for="input3">Input 3</label>
      <input type="text" class="form-control" id="input3" placeholder="Enter Input 3">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="button" class="btn btn-primary next">Next</button>
  </form>

  <form id="step4" style="display: none;">
    <h2>Step 4</h2>
    <div class="form-group">
      <label for="input4">Input 4</label>
      <input type="text" class="form-control" id="input4" placeholder="Enter Input 4">
    </div>
    <button type="button" class="btn btn-secondary prev">Previous</button>
    <button type="submit" class="btn btn-success">Submit</button>
  </form>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $(document).ready(function() {
    var currentStep = 1;

    $('.next').click(function() {
      $('#step' + currentStep).hide();
      currentStep++;
      $('#step' + currentStep).show();
      updateProgressBar();
    });

    $('.prev').click(function() {
      $('#step' + currentStep).hide();
      currentStep--;
      $('#step' + currentStep).show();
      updateProgressBar();
    });

    function updateProgressBar() {
      var progress = (currentStep - 1) * 33.33;
      $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress);
    }
  });
</script>

</body>
</html>
