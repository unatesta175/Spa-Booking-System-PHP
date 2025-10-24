let navbar = document.querySelector('.header .flex .navbar');
let profile = document.querySelector('.header .flex .profile');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

let mainImage = document.querySelector('.update-product .image-container .main-image img');
let subImages = document.querySelectorAll('.update-product .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});

var customStyles = `
/* Define the font size for the popup */
.my-custom-popup {
    font-size: 16px; /* Change the font size as needed */
}

/* Define the font size for the title */
.my-custom-title {
    font-size: 20px; /* Change the font size as needed */
}

/* Define the font size for the text */
.my-custom-text {
    font-size: 18px; /* Change the font size as needed */
}

/* Define the font size for the confirm button */
.my-custom-confirm-button {
    font-size: 16px; /* Change the font size as needed */
}

/* Define the font size for the cancel button */
.my-custom-cancel-button {
    font-size: 16px; /* Change the font size as needed */
}

`;

// Create a <style> element
var styleElement = document.createElement('style');

// Set the CSS text for the <style> element
styleElement.textContent = customStyles;

// Append the <style> element to the <head> of the document
document.head.appendChild(styleElement);

// Add event listener to the logout button
document.getElementById('logoutButton').addEventListener('click', function() {
    Swal.fire({
        title: 'Anda Pasti?',
        text: "Anda ingin log keluar?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Log Keluar!',
        cancelButtonText: 'Batal',
        customClass: {
            // Define a class for the SweetAlert popup
            popup: 'my-custom-popup',
            // Define a class for the SweetAlert title
            title: 'my-custom-title',
            // Define a class for the SweetAlert text
            text: 'my-custom-text',
            // Define a class for the SweetAlert confirm button
            confirmButton: 'my-custom-confirm-button',
            // Define a class for the SweetAlert cancel button
            cancelButton: 'my-custom-cancel-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the logout page if the user confirms
            window.location.href = '../components/admin-logout.php';
        }
    });
});