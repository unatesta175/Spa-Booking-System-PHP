<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#service").change(function(){
            var selected = $(this).val();
			var datev = $("#date").val(); // Get date input value
			var staffv = $("#staff").val();
			 // Get date input value
            var datep = <?php echo json_encode($_POST['postdate']); ?>;
            var staffp = <?php echo json_encode($_POST['poststaff']); ?>; // Echo PHP variable into JavaScript
            
            // Check if the selected value is not "---Select---"
            if (selected !== "---Select---" && datev !== "dd/mm/yyyy" && staffv !== "---Select---") {
                $("#employee_div").load("search.php", {service: selected, staff: staffp, date: datep});
            }else{$("#employee_div").load("search.php",}
        });
    });
</script>



						
