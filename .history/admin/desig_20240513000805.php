<?php

include '../components/connect.php'; ?>

<script type="text/javascript">
	$(document).ready(function(){
    // Function to check if all three inputs have non-default values
	function checkInputs() {
            var date = $("#date").val();
            var staff = $("#depart_dropdown").val();
            var service = $("#desig_dropdown").val();

            // Check if all inputs have non-default values
            if (date && staff && service) {
                // Load search.php only if all inputs are set
                $("#employee_div").load("search.php", {
                    selected_desig: service,
                    bruhh: staff,
                    datee: date
                });
            }
        }

        // Call checkInputs when any input changes
        $("#date, #depart_dropdown, #desig_dropdown").change(function() {
            checkInputs();
        });

        // Initially check if all inputs are set
        checkInputs();
		
	});
</script>



						
