document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById("menu-toggle");
  const navContainer = document.getElementById("nav-container");
  const dropdownTriggers = document.querySelectorAll(".dropdown-trigger");

  if (menuToggle && navContainer) {
    menuToggle.addEventListener("click", () => {
      menuToggle.classList.toggle("active");
      navContainer.classList.toggle("active");

      // Prevent body scroll when menu is open on mobile
      if (window.innerWidth <= 1024) {
        document.body.style.overflow = navContainer.classList.contains("active")
          ? "hidden"
          : "";
      }
    });

    // Close menu when clicking links (except dropdown triggers)
    const navLinks = navContainer.querySelectorAll(
      ".nav-link:not(.dropdown-trigger), .dropdown-item"
    );
    navLinks.forEach((link) => {
      link.addEventListener("click", () => {
        menuToggle.classList.remove("active");
        navContainer.classList.remove("active");
        document.body.style.overflow = "";
      });
    });
  }

  // Handle dropdowns on mobile/tablet
  dropdownTriggers.forEach((trigger) => {
    trigger.addEventListener("click", (e) => {
      if (window.innerWidth <= 1024) {
        const dropdown = trigger.closest(".dropdown");
        if (dropdown) {
          e.preventDefault();
          dropdown.classList.toggle("active");

          // Close other dropdowns
          document.querySelectorAll(".dropdown").forEach((d) => {
            if (d !== dropdown) d.classList.remove("active");
          });
        }
      }
    });
  });

  // Reset mobile styles on window resize
  window.addEventListener("resize", () => {
    if (window.innerWidth > 1024) {
      if (menuToggle) menuToggle.classList.remove("active");
      if (navContainer) navContainer.classList.remove("active");
      document.body.style.overflow = "";
      document
        .querySelectorAll(".dropdown")
        .forEach((d) => d.classList.remove("active"));
    }
  });
});