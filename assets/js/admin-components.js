/* Navigation */
const adminPage = document.body.dataset.adminPage || "";
const items = [
  ["dashboard", "dashboard.php", "bi-grid", "Dashboard"],
  ["blogs", "blogs.php", "bi-journal-bookmark", "Blogs"],
  ["blog_form", "blog_form.php", "bi-journal-plus", "Add blog"],
  ["users", "users.php", "bi-people", "Users"],
  ["posts", "posts.php", "bi-file-text", "Posts"],
  ["categories", "categories.php", "bi-tags", "Categories"],
  ["comments", "comments.php", "bi-chat-left-text", "Comments"],
  ["feedback", "feedback.php", "bi-envelope", "Feedback"],
  ["settings", "settings.php", "bi-sliders", "Blog settings"],
];
document
  .querySelector("[data-admin-sidebar]")
  ?.insertAdjacentHTML(
    "afterbegin",
    `<aside class="admin-sidebar" id="adminSidebar"><button class="admin-sidebar-close" type="button" data-admin-close aria-label="Close administrator menu"><i class="bi bi-x-lg" aria-hidden="true"></i></button><a class="brand d-block mb-4" href="dashboard.php">Tales</a><small class="text-uppercase text-white-50">Administrator</small><nav class="nav flex-column mt-2">${items.map((i) => `<a class="nav-link ${adminPage === i[0] ? "active" : ""}" href="${i[1]}"><i class="bi ${i[2]} me-2"></i>${i[3]}</a>`).join("")}</nav><hr class="border-secondary"><a class="nav-link" href="../index.php"><i class="bi bi-box-arrow-left me-2"></i>View site</a><a class="nav-link" href="../logout.php"><i class="bi bi-power me-2"></i>Sign out</a></aside>`,
  );
document
  .querySelector("[data-admin-topbar]")
  ?.insertAdjacentHTML(
    "afterbegin",
    `<header class="admin-topbar"><div><button class="btn btn-outline-primary mobile-admin-toggle me-2" type="button" data-admin-toggle aria-controls="adminSidebar" aria-expanded="false" aria-label="Open administrator menu"><i class="bi bi-list" aria-hidden="true"></i></button><span class="text-muted">Sunday, July 19, 2026</span></div><div style="display: flex; align-items: center; gap: 1rem;"><div class="dropdown"><button class="btn bg-white dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static"><img class="avatar me-2" src="../assets/images/admin/kid_admin_avatar.jpg?v=youngadmin2026" alt="Admin">Admin</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="settings.php">Settings</a></li><li><a class="dropdown-item" href="../logout.php">Sign out</a></li></ul></div></div></header>`,
  );

/* Navigation */
(() => {
  const shell = document.querySelector(".admin-shell");
  const sidebar = document.querySelector(".admin-sidebar");
  const toggle = document.querySelector("[data-admin-toggle]");
  const close_button = document.querySelector("[data-admin-close]");
  if (!shell || !sidebar || !toggle) return;

  const backdrop = document.createElement("button");
  backdrop.type = "button";
  backdrop.className = "admin-sidebar-backdrop";
  backdrop.setAttribute("aria-label", "Close administrator menu");
  shell.append(backdrop);

  const is_mobile = () => window.innerWidth < 992;
  const update_toggle = (open) => {
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute(
      "aria-label",
      open ? "Close administrator menu" : "Open administrator menu",
    );
        toggle.classList.toggle("menu-expanded", open);
  };

  const close_mobile = () => {
    sidebar.classList.remove("open");
    backdrop.classList.remove("show");
    document.body.classList.remove("admin-menu-open");
    update_toggle(false);
  };

  toggle.addEventListener("click", () => {
    if (is_mobile()) {
      const open = !sidebar.classList.contains("open");
      sidebar.classList.toggle("open", open);
      backdrop.classList.toggle("show", open);
      document.body.classList.toggle("admin-menu-open", open);
      update_toggle(open);
      return;
    }

    const collapsed = !shell.classList.contains("sidebar-collapsed");
    shell.classList.toggle("sidebar-collapsed", collapsed);
    update_toggle(!collapsed);
  });

  backdrop.addEventListener("click", close_mobile);
  close_button?.addEventListener("click", () => {
    if (is_mobile()) {
      close_mobile();
    } else {
      shell.classList.add("sidebar-collapsed");
      update_toggle(false);
    }
  });
  sidebar.addEventListener("click", (event) => {
    if (is_mobile() && event.target.closest("a")) close_mobile();
  });
  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") close_mobile();
  });
  window.addEventListener("resize", () => {
    if (!is_mobile()) {
      close_mobile();
      update_toggle(!shell.classList.contains("sidebar-collapsed"));
    }
  });

  update_toggle(!is_mobile());
})();


