<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send WhatsApp Message</title>
</head>
<body>

<!-- Input for phone number -->
<label for="phone">Enter Phone Number (International Format):</label>
<input type="text" id="phone" placeholder="60123456789">

<!-- Input for message -->
<label for="message">Enter Message:</label>
<textarea id="message" placeholder="Hello, I am interested in your services."></textarea>

<!-- Button to open WhatsApp -->
<button onclick="sendWhatsApp()">Send Message to WhatsApp</button>

<script>
function sendWhatsApp() {
    // Get the phone number and message from inputs
    var phone = document.getElementById('phone').value;
    var message = document.getElementById('message').value;

    // URL encode the message
    var encodedMessage = encodeURIComponent(message);

    // Construct the WhatsApp URL
    var whatsappURL = 'https://wa.me/' + phone + '?text=' + encodedMessage;

    // Open the WhatsApp URL in a new tab
    window.open(whatsappURL, '_blank');
}
</script>

</body>
</html>