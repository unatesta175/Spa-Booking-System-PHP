<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
    $(document).ready(function(){
        $("#service").change(function(){
            var selected = $(this).val();
            var date = <?php echo json_encode($_POST['postdate']); ?>;
            var service = <?php echo json_encode($_POST['postservice']); ?>; // Echo PHP variable into JavaScript
            
            // Check if the selected value is not "---Select---"
            if (selected !== "---Select---") {
                $("#employee_div").load("search.php", {selected_desig: selected, bruhh: bruh, datee: date});
            }
        });
    });
</script>



						
