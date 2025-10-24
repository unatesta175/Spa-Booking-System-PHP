<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#service").change(function(){
            var selected = $(this).val();
            var datep = <?php echo json_encode($_POST['date']); ?>;
            var staffp = <?php echo json_encode($_POST['poststaff']); ?>; // Echo PHP variable into JavaScript
            
            // Check if the selected value is not "---Select---"
            if (selected !== "---Select---") {
                $("#employee_div").load("search.php", {service: selected, staff: staffp, date: datep});
            }
        });
    });
</script>



						
