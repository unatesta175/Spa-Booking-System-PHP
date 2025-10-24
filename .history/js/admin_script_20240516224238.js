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