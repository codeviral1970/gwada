const toggleMenuBtn = document.querySelector("#menu-btn");
const toggleMenuImg = document.querySelector("#menu-btn img");
const toggledMenu = document.querySelector("#toggled-menu");
const menuLinks = document.querySelector("#main-nav ul a");

toggleMenuBtn.addEventListener("click", toggleNav);

function toggleNav() {
  toggledMenu.classList.toggle("-translate-y-full")

  if (toggledMenu.classList.contains("-translate-y-full"))
  {
    toggleMenuImg.setAttribute("src", "build/images/menu.svg")
    toggleMenuBtn.setAttribute("aria-expanded", "false")
  }
  else
  {
    toggleMenuImg.setAttribute("src", "build/images/cross.svg")
    toggleMenuBtn.setAttribute("aria-expanded", "true")
  }
}

setTimeout(() => {
  const success = document.getElementById('success-message');

  // 👇️ hides element (still takes up space on page)
  success.style.visibility = 'hidden';
}, 1000);

setTimeout(() => {
  const alert = document.getElementById('alert');

  alert.style.display = 'none';
}, 5000);