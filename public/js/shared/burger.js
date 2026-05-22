const burger = document.querySelector('.navbar__burger');
const navLinks = document.querySelector('.navbar__links');

if (burger && navLinks) {
    burger.addEventListener('click', () => {
        navLinks.classList.toggle('navbar__links--open');
    });
}
