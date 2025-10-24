// Function to fetch service duration based on selected service ID
function fetchServiceDuration(serviceId) {
   $.ajax({
      url: 'fetch_service_duration.php', // Assuming this is the correct endpoint
      type: 'GET',
      data: { service: serviceId },
      success: function (response) {
         // Update the duration input field with the fetched duration
         $("#duration").val(response); // Assuming response contains the duration value
      },
      error: function (xhr, status, error) {
         console.error(xhr.responseText);
      }
   });
}
