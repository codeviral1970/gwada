(function () {
  'use strict';

  console.log('js run...');

  const toggleMenuBtn = document.querySelector("#menu-btn");
  const toggleMenuImg = document.querySelector("#menu-btn img");
  const toggledMenu = document.querySelector("#toggled-menu");
  const menuLinks = document.querySelector("#main-nav ul a");

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

  toggleMenuBtn.addEventListener("click", toggleNav);

  window.onload = function () {

    toggleMenuBtn.addEventListener("click", toggleNav);

  }


  setTimeout(() => {
    const success = document.getElementById('success-message');
    if (success)
    {
      success.hidden = true;
    }
  }, 4000);

  setTimeout(() => {
    const alert = document.getElementById('alert');
    if (alert)
    {
      alert.hidden = true;
    }
  }, 5000);


})();
