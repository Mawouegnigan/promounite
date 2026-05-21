// header.js - menu hamburger
const menuToggle = document.getElementById('menuToggle');
const navbar = document.querySelector('.navbar');

if(menuToggle && navbar){
    menuToggle.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
}