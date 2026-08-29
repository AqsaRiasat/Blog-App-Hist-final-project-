/* Navigation */
const activePage = document.body.dataset.page || "";

/* Navigation */
if (!document.querySelector('link[href*="bootstrap-icons"]')) {
  const iconStyles = document.createElement("link");
  iconStyles.rel = "stylesheet";
  iconStyles.href = "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css";
  document.head.appendChild(iconStyles);
}
const publicNav = `<nav class="navbar navbar-expand-lg site-nav fixed-top"><div class="container nav-layout"><a class="navbar-brand brand" href="index.php">Tales</a><button class="navbar-toggler" type="button" aria-controls="mainNav" aria-expanded="false" aria-label="Open navigation"><span class="public-menu-icon" aria-hidden="true"><span></span><span></span><span></span></span></button><div class="collapse navbar-collapse" id="mainNav"><ul class="navbar-nav nav-center"><li class="nav-item"><a class="nav-link ${activePage === "home" ? "active" : ""}" href="index.php">Home</a></li><li class="nav-item"><a class="nav-link ${activePage === "blogs" ? "active" : ""}" href="blogs.php">Blogs</a></li><li class="nav-item"><a class="nav-link ${activePage === "categories" ? "active" : ""}" href="categories.php">Categories</a></li><li class="nav-item"><a class="nav-link ${activePage === "about" ? "active" : ""}" href="about.php">About</a></li><li class="nav-item"><a class="nav-link ${activePage === "contact" ? "active" : ""}" href="contact.php">Contact</a></li></ul><div class="nav-actions" style="display: flex; align-items: center; gap: 0.5rem;"><a class="btn btn-outline-primary btn-sm" href="login.php">Sign in</a><a class="btn btn-primary btn-sm" href="register.php">Join us</a></div></div></div></nav>`;
const footer = `<footer class="footer"><div class="container"><div class="row g-4"><div class="col-lg-5"><h3 class="text-white">Tales</h3><p>Gentle bedtime tales, animal adventures, fairy tales, magical worlds, and stories with heart.</p></div><div class="col-6 col-lg-2"><h6 class="text-white">Explore</h6><a class="d-block" href="index.php"><i class="bi bi-journal-richtext me-2" aria-hidden="true"></i>Latest stories</a><a class="d-block" href="blogs.php"><i class="bi bi-collection me-2" aria-hidden="true"></i>Blogs</a><a class="d-block" href="categories.php"><i class="bi bi-grid me-2" aria-hidden="true"></i>Categories</a><a class="d-block" href="about.php"><i class="bi bi-info-circle me-2" aria-hidden="true"></i>About</a></div><div class="col-6 col-lg-2"><h6 class="text-white">Account</h6><a class="d-block" href="login.php"><i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Sign in</a><a class="d-block" href="register.php"><i class="bi bi-person-plus me-2" aria-hidden="true"></i>Register</a><a class="d-block" href="profile.php"><i class="bi bi-person-circle me-2" aria-hidden="true"></i>Profile</a></div><div class="col-lg-3"><h6 class="text-white">Follow new stories</h6><a class="btn btn-light btn-sm" href="index.php#follow-blog"><i class="bi bi-bell me-1"></i> Follow blog</a></div></div><hr class="border-secondary my-4"><small>&copy; 2026 Tales Online Blogging Application.</small></div></footer>`;
document
  .querySelector("[data-public-nav]")
  ?.insertAdjacentHTML("afterbegin", publicNav);

/* Setup */
fetch("includes/navigation_state.php", {
  method: "GET",
  credentials: "same-origin",
  cache: "no-store",
})
  .then((response) => response.json())
  .then((state) => {
    if (!state.logged_in) return;

    const navActions = document.querySelector(".site-nav .nav-actions");
    if (!navActions) return;

    if (state.is_admin) {
      navActions.innerHTML = `
        <a class="btn btn-outline-primary btn-sm" href="admin/dashboard.php">Admin Dashboard</a>
        <a class="btn btn-primary btn-sm" href="logout.php">Sign Out</a>
      `;
    } else {
      navActions.innerHTML = `
        <a class="btn btn-outline-primary btn-sm" href="profile.php">My Profile</a>
        <a class="btn btn-primary btn-sm" href="logout.php">Sign Out</a>
      `;
    }
  });
document
  .querySelector("[data-footer]")
  ?.insertAdjacentHTML("afterbegin", footer);

/* Navigation */
(() => {
  const nav = document.querySelector(".site-nav");
  const toggle = nav?.querySelector(".navbar-toggler");
  const menu = nav?.querySelector(".navbar-collapse");
  if (!nav || !toggle || !menu) return;

  const set_menu = (open) => {
    menu.classList.toggle("show", open);
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Close navigation" : "Open navigation");
        toggle.classList.toggle("is-open", open);
  };

  toggle.addEventListener("click", () => {
    set_menu(!menu.classList.contains("show"));
  });

  menu.addEventListener("click", (event) => {
    if (event.target.closest("a") && window.innerWidth < 1200) set_menu(false);
  });

  document.addEventListener("click", (event) => {
    if (window.innerWidth < 1200 && !nav.contains(event.target)) set_menu(false);
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 1200) set_menu(false);
  });
})();


