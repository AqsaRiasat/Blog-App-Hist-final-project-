

document.addEventListener("DOMContentLoaded", () => {
  const nav = document.querySelector(".site-nav");
  const on_scroll = () => nav?.classList.toggle("scrolled", scrollY > 20);
  on_scroll();
  addEventListener("scroll", on_scroll);

  /* Animation */
  const set_aos = (selector, animation, stagger = 0) => {
    document.querySelectorAll(selector).forEach((element, index) => {
      if (element.closest(".modal")) return;
      element.classList.remove(
        "reveal",
        "visible",
        "motion-fade",
        "motion-zoom",
        "motion-down",
        "motion-up",
        "motion-left",
        "motion-right",
      );
      element.setAttribute("data-aos", animation);
      element.setAttribute("data-aos-duration", "900");
      if (stagger)
        element.setAttribute(
          "data-aos-delay",
          String(Math.min(index * stagger, 350)),
        );
    });
  };

  set_aos(".page-hero .container, .hero-card > div, .ref-hero-copy", "fade-up");
  set_aos(
    "main section > .container > .ref-centered-title h2, main section > .container > .category-card-heading h2, main section > .container > .category-results-heading h2",
    "zoom-in",
  );
  set_aos(
    "main section .ref-kicker, main section .eyebrow, .admin-main > .eyebrow, .admin-main > h1, .admin-topbar",
    "fade-down",
  );
  set_aos(".ref-feature-card i, .stat-icon", "zoom-in", 70);
  set_aos(
    ".ref-facts-grid > *, .category-page-card-grid > *, .category-post-grid > *, .ref-news .row.g-4 > *, .home-post-mosaic > *, .admin-main .row.g-3 > *, .admin-main .row.g-4 > *",
    "fade-up",
    70,
  );
  set_aos(
    ".ref-roadmap .row > :first-child",
    "fade-right",
  );
  set_aos(
    ".ref-roadmap .row > :last-child",
    "fade-left",
  );
  set_aos(
    ".ref-person, .ref-advisor, .post-card, .ref-news-card, .ref-publication, .ref-recent, .recent-item, .comment, .author-row, .admin-main tbody tr",
    "fade-up",
    60,
  );
  set_aos(
    ".auth-card, .admin-main > .surface, .admin-main > form, .admin-main .table-responsive, main > article, body > main.auth-wrap > .container",
    "fade-up",
  );
  set_aos(
    ".subscribe-band .container, .ref-subscribe-inner, footer .container",
    "fade-up",
  );
  set_aos(
    'body:not(.home-reference):not(.category-posts-page) main .section .row > [class*="col-"]:nth-child(odd)',
    "fade-right",
  );
  set_aos(
    'body:not(.home-reference):not(.category-posts-page) main .section .row > [class*="col-"]:nth-child(even)',
    "fade-left",
  );

  
  set_aos('body[data-page="about"] .page-hero .container', "fade-down");
  set_aos('body[data-page="about"] main .row > :first-child', "fade-right");
  set_aos('body[data-page="about"] main .row > :last-child', "fade-left");
  /* Contact */
  document
    .querySelectorAll('body[data-page="contact"] main [data-aos]')
    .forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  set_aos('body[data-page="contact"] .page-hero .container', "fade-down");
  set_aos(
    'body[data-page="contact"] main.section > .container > .row > :first-child',
    "fade-right",
  );
  set_aos(
    'body[data-page="contact"] main.section > .container > .row > :last-child > .surface',
    "fade-left",
  );
  /* Profile */
  document
    .querySelectorAll('body[data-page="profile"] main [data-aos]')
    .forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  set_aos('body[data-page="profile"] .page-hero .container', "fade-down");
  set_aos(
    'body[data-page="profile"] main .container > .row > :first-child > .surface',
    "fade-right",
  );
  set_aos(
    'body[data-page="profile"] main .container > .row > :last-child > .surface',
    "fade-left",
  );

  /* Forms */
  document
    .querySelectorAll('body[data-page="login"] main [data-aos]')
    .forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  set_aos('body[data-page="login"] .auth-card > .brand', "fade-down");
  set_aos(
    'body[data-page="login"] .auth-card > .row > :first-child',
    "fade-right",
  );
  set_aos(
    'body[data-page="login"] .auth-card > .row > :last-child',
    "fade-left",
  );

  /* Authentication */
  document
    .querySelectorAll('body[data-page="register"] main [data-aos]')
    .forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  set_aos('body[data-page="register"] .auth-intro', "fade-down");
  set_aos('body[data-page="register"] .auth-card > form', "fade-up");
  /* Admin */
  if (document.body.dataset.adminPage) {
    document.querySelectorAll("[data-aos]").forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  }
  /* Posts */
  if (document.body.classList.contains("home-reference")) {
    const posts_section = document.querySelector("#market-news");
    posts_section?.querySelectorAll("[data-aos]").forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
    set_aos("#market-news > .container > .ref-centered-title", "fade-up");
    set_aos("#market-news .latest-posts-layout > article", "fade-up", 110);
    set_aos("#market-news .latest-search-form", "fade-left");
    set_aos("#market-news .recent-stories-panel", "fade-left");
    document
      .querySelector("#market-news .latest-search-form")
      ?.setAttribute("data-aos-delay", "180");
    set_aos("#market-news .pagination", "fade-up");
    document
      .querySelector("#market-news .pagination")
      ?.setAttribute("data-aos-delay", "320");
  }

  /* Animation */
  set_aos(".premium-page-hero > .container", "fade-up");
  set_aos('body[data-page="blogs"] .blogs-directory-heading', "fade-right");
  set_aos('body[data-page="blogs"] .blog-directory-row', "fade-up", 100);
  set_aos("body.category-posts-page .category-card-heading", "fade-up");
  set_aos("body.category-posts-page .category-page-card-grid > a", "fade-up", 90);
  set_aos('body[data-page="post-detail"] .article-cover', "zoom-in");
  set_aos('body[data-page="post-detail"] .article-body', "fade-up");
  set_aos('body[data-page="post-detail"] article section', "fade-up", 100);
  /* Authentication */
  document
    .querySelectorAll('body[data-page="forgot-password"] main [data-aos]')
    .forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  set_aos('body[data-page="forgot-password"] .auth-intro', "fade-down");
  set_aos('body[data-page="forgot-password"] .auth-card > form', "fade-up");
  /* Footer */
  set_aos(".footer > .container", "fade-up");

  /* Forms */
  document.querySelectorAll("[data-aos]").forEach((element) => {
    element.setAttribute("data-aos-duration", "900");
    element.setAttribute("data-aos-easing", "ease-out-cubic");
    element.setAttribute("data-aos-offset", "90");
    element.setAttribute("data-aos-anchor-placement", "top-bottom");
  });
  const reduced_motion = matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (window.AOS && !reduced_motion && !document.body.dataset.adminPage) {
    AOS.init({
      duration: 900,
      easing: "ease-out-cubic",
      once: true,
      mirror: false,
      offset: 90,
      anchorPlacement: "top-bottom",
    });
    document.documentElement.classList.add("aos-enabled");
    setTimeout(() => AOS.refreshHard(), 120);
  } else {
    document.querySelectorAll("[data-aos]").forEach((element) => {
      element.removeAttribute("data-aos");
      element.removeAttribute("data-aos-delay");
      element.removeAttribute("data-aos-duration");
    });
  }
  /* Cursor */
  const glow = document.createElement("div");
  glow.className = "golden-glow-follower";
  glow.setAttribute("aria-hidden", "true");
  document.body.appendChild(glow);

  let pointer_x = innerWidth / 2;
  let pointer_y = innerHeight / 2;
  let glow_x = pointer_x;
  let glow_y = pointer_y;

  addEventListener(
    "pointermove",
    (event) => {
      if (event.pointerType === "touch") return;
      pointer_x = event.clientX;
      pointer_y = event.clientY;
      document.documentElement.classList.add("has-glow-cursor");
      glow.classList.add("is-visible");
    },
    { passive: true },
  );

  document.documentElement.addEventListener("mouseleave", () =>
    glow.classList.remove("is-visible"),
  );
  document.documentElement.addEventListener("mouseenter", () => {
    if (document.documentElement.classList.contains("has-glow-cursor"))
      glow.classList.add("is-visible");
  });

  const animate_glow = () => {
    glow_x += (pointer_x - glow_x) * 0.18;
    glow_y += (pointer_y - glow_y) * 0.18;
    glow.style.transform = `translate3d(${glow_x}px, ${glow_y}px, 0) translate(-50%, -50%)`;
    requestAnimationFrame(animate_glow);
  };
  requestAnimationFrame(animate_glow);
  /* Forms */
  const form_close_configs = [
    ['body[data-page="login"] .auth-card', "index.php"],
    ['body[data-page="register"] .auth-card', "login.php"],
    ['body[data-page="forgot-password"] .auth-card', "login.php"],
    ['body[data-page="contact"] main .surface:has(form)', "index.php"],
    ['body[data-page="profile"] main .surface:has(form)', "index.php"],
    [
      'body[data-admin-page="categories"] .admin-main form.surface',
      "dashboard.php",
    ],
    ['body[data-admin-page="posts"] .admin-main > form', "posts.php"],
    ['body[data-admin-page="settings"] .admin-main > form', "dashboard.php"],
  ];

  form_close_configs.forEach(([selector, href]) => {
    document.querySelectorAll(selector).forEach((panel) => {
      if (panel.querySelector(":scope > .form-panel-close")) return;
      panel.classList.add("closable-form-panel");
      const close_link = document.createElement("a");
      close_link.className = "form-panel-close";
      close_link.href = href;
      close_link.setAttribute("aria-label", "Close form");
      close_link.innerHTML = '<span aria-hidden="true">&times;</span>';
      panel.prepend(close_link);
    });
  });

  /* Authentication */
  document.querySelectorAll("[data-password-toggle]").forEach((button) =>
    button.addEventListener("click", () => {
      const input = document.getElementById(button.dataset.passwordToggle);
      if (input) {
        input.type = input.type === "password" ? "text" : "password";
        button.querySelector("i")?.classList.toggle("bi-eye-slash");
      }
    }),
  );

  /* Forms */
  const validate_matching_fields = (form) => {
    const pairs = [
      ["email", "confirm_email", "Email addresses must match."],
      ["password", "confirm_password", "Passwords must match."],
      ["new_password", "confirm_new_password", "Passwords must match."],
    ];
    pairs.forEach(([source_name, confirmation_name, message]) => {
      const source = form.querySelector(`[name="${source_name}"]`);
      const confirmation = form.querySelector(`[name="${confirmation_name}"]`);
      if (!source || !confirmation) return;
      confirmation.setCustomValidity(
        source.value === confirmation.value ? "" : message,
      );
    });
  };

  const validate_image_size = (input) => {
    const maximum_size = Number(input.dataset.maxSize || 1048576);
    const file = input.files?.[0];
    input.setCustomValidity(
      file && file.size > maximum_size ? "Image must not exceed 1 MB." : "",
    );
  };

  document
    .querySelectorAll('input[type="file"][name="image"]')
    .forEach((input) => {
      input.addEventListener("change", () => validate_image_size(input));
    });

  document.querySelectorAll(".needs-validation").forEach((form) => {
    form
      .querySelectorAll(
        '[name="email"], [name="confirm_email"], [name="password"], [name="confirm_password"], [name="new_password"], [name="confirm_new_password"]',
      )
      .forEach((input) => {
        input.addEventListener("input", () => validate_matching_fields(form));
      });
    form.addEventListener("submit", (event) => {
      validate_matching_fields(form);
      form
        .querySelectorAll('input[type="file"][name="image"]')
        .forEach(validate_image_size);
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add("was-validated");
    });
  });

  /* Contact */
  document.querySelectorAll("[data-demo-action]").forEach((element) =>
    element.addEventListener("click", (event) => {
      const form = element.closest("form");
      if (form) {
        validate_matching_fields(form);
        form
          .querySelectorAll('input[type="file"][name="image"]')
          .forEach(validate_image_size);
        form.classList.add("was-validated");
        if (!form.checkValidity()) return;
      }
      event.preventDefault();
      const box = document.getElementById("demo-alert");
      if (box) {
        box.className = "alert alert-success";
        box.textContent = element.dataset.demoAction;
        box.hidden = false;
        box.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }),
  );

  /* Profile */
  document.querySelectorAll("[data-edit-user]").forEach((button) => {
    button.addEventListener("click", () => {
      const edit_form = document.querySelector("#editUserModal form");
      if (!edit_form) return;

      edit_form.querySelector('[name="user_id"]').value = button.dataset.userId;
      edit_form.querySelector('[name="first_name"]').value = button.dataset.firstName;
      edit_form.querySelector('[name="last_name"]').value = button.dataset.lastName;
      edit_form.querySelector('[name="email"]').value = button.dataset.email;
      edit_form.querySelector('[name="gender"]').value = button.dataset.gender;
      edit_form.querySelector('[name="date_of_birth"]').value = button.dataset.dateOfBirth;
      edit_form.querySelector('[name="home_town"]').value = button.dataset.homeTown;
      edit_form.querySelector('[name="account_status"]').value = button.dataset.accountStatus;
      edit_form.classList.remove("was-validated");
    });
  });
  /* Comments */
  document.querySelectorAll("[data-edit-comment]").forEach((button) => {
    button.addEventListener("click", () => {
      const edit_form = document.querySelector("#editCommentModal form");
      if (!edit_form) return;

      edit_form.querySelector('[name="comment_id"]').value = button.dataset.commentId;
      edit_form.querySelector('[name="comment"]').value = button.dataset.commentText;
      edit_form.querySelector('[name="status"]').value = button.dataset.commentStatus;
      edit_form.classList.remove("was-validated");
    });
  });
  /* Search */
  const search = document.querySelector("[data-table-search]");
  if (search)
    search.addEventListener("input", () => {
      const query = search.value.toLowerCase().trim();
      document.querySelectorAll("[data-search-row]").forEach((row) => {
        row.hidden = !row.textContent.toLowerCase().includes(query);
      });
    });

  /* Categories */
  const category_names = {
    "animal-adventures": "Animal Adventures",
    "bedtime-stories": "Bedtime Stories",
    "fairy-tales": "Fairy Tales",
    "magical-worlds": "Magical Worlds",
    "moral-stories": "Moral Stories",
  };
  const selected_category = new URLSearchParams(location.search).get(
    "category",
  );
  if (selected_category && category_names[selected_category]) {
    document.querySelectorAll("[data-post-category]").forEach((card) => {
      card.hidden = card.dataset.postCategory !== selected_category;
    });
    const section_title = document.getElementById("posts_section_title");
    if (section_title)
      section_title.textContent = `${category_names[selected_category]} Posts`;
  }
});




