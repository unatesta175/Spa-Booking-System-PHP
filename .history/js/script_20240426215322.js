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

let mainImage = document.querySelector('.quick-view .box .row .image-container .main-image img');
let subImages = document.querySelectorAll('.quick-view .box .row .image-container .sub-image img');

subImages.forEach(images =>{
   images.onclick = () =>{
      src = images.getAttribute('src');
      mainImage.src = src;
   }
});

document.addEventListener('DOMContentLoaded', function() {
   var dropdownToggles = document.querySelectorAll('.dropdown-toggle-wrapper'); // Adjusted to select the new wrapper
   dropdownToggles.forEach(function(toggle) {
       toggle.onclick = function(event) {
           event.stopPropagation();
           var dropdownContent = this.nextElementSibling;
           if (dropdownContent && dropdownContent.classList.contains('dropdown-content')) {
               dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
           }
       };
   });

   document.addEventListener('click', function(event) {
       var dropdownContents = document.querySelectorAll('.dropdown-content');
       dropdownContents.forEach(function(content) {
           if (!content.contains(event.target)) {
               content.style.display = 'none';
           }
       });
   });
});


var customStyles = `
    /* Your styles for the SweetAlert popup */
    .my-custom-popup {
        /* Example:
        background-color: #f0f0f0;
        */
    }

    /* Your styles for the SweetAlert title */
    .my-custom-title {
        /* Example:
        color: #ff0000;
        */
    }

    /* Your styles for the SweetAlert text */
    .my-custom-text {
        /* Example:
        font-size: 16px;
        */
    }

    /* Your styles for the SweetAlert confirm button */
    .my-custom-confirm-button {
        /* Example:
        background-color: #00ff00;
        */
    }

    /* Your styles for the SweetAlert cancel button */
    .my-custom-cancel-button {
        /* Example:
        background-color: #ff0000;
        */
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
            window.location.href = 'components/user-logout.php';
        }
    });
});

