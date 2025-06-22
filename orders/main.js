const menu = document.querySelector(".nav-links");
const navLinks = document.querySelectorAll(".nav-links a");
function mobileMenu() {
  menu.classList.add("active");
}
function closeMenu() {
  menu.classList.remove("active");
}
navLinks.forEach(link => {
  link.addEventListener("click", closeMenu);
});
