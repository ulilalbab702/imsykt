document.addEventListener("DOMContentLoaded", function () {
  const links = document.querySelectorAll(".sidebar .nav-link");
  const currentUrl = window.location.pathname;

  links.forEach((link) => {
    if (
      link.getAttribute("href") &&
      currentUrl.includes(link.getAttribute("href"))
    ) {
      link.classList.add("active");
    }
  });
});
