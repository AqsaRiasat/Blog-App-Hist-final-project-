<?php

require "includes/session.php";

$login_field_error = "";

if ( isset( $_SESSION["login_error"] ) ) {
    $login_field_error = $_SESSION["login_error"];
    $_SESSION["login_error"] = null;
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Sign in | Tales</title>
    <!-- Theme -->
    <link
      href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <!-- Scripts -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
  </head>
  <body data-page="login">
    <?php require "includes/popup_message.php"; ?>

    <!-- Content -->
    <!-- Forms -->

    <main class="auth-wrap">
      <div class="container">
        <div class="surface auth-card">
          <!-- Left side illustration panel -->
          <div class="auth-illustration-side col-md-5 d-none d-md-flex">
            <div class="auth-illustration-image-wrap">
              <img src="assets/images/auth/login_illustration.jpg" alt="Welcome to Tales">
            </div>
          </div>
          
          <!-- Right side form panel -->
          <div class="auth-form-side col-md-7 col-12">
            <!-- Brand title for mobile/small screen only -->
            <a class="brand h3 d-block d-md-none mb-4" href="index.php" style="color: #121212 !important; font-weight: 800;">Tales</a>
            <h2>Sign in</h2>
            <p class="form-subtitle">Enjoy stories, share kind comments, and manage your profile.</p>

            <form
              class="needs-validation"
              novalidate
              action="actions/login_action.php"
              method="post"
            >
              <div class="mb-3">
                <label class="form-label" for="login_email"
                  >Email address</label
                ><input
                  class="form-control"
                  id="login_email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  placeholder="you@example.com"
                  required
                />
                <div class="invalid-feedback">
                  Enter a valid email address.
                </div><?php if ( $login_field_error != "" ) { ?><div class="server-field-error"><?php echo $login_field_error; ?></div><?php } ?>
              </div>
              <div class="mb-2">
                <label class="form-label" for="login_password"
                  >Password</label
                >
                <div class="input-group">
                  <input
                    class="form-control"
                    id="login_password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    minlength="8"
                    required
                  /><button
                    class="btn btn-outline-secondary"
                    type="button"
                    data-password-toggle="login_password"
                    aria-label="Show password"
                  >
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
              <div class="text-end my-3">
                <a href="forgot_password.php">Forgot password?</a>
              </div>
              <button
                class="btn btn-primary w-100"
                type="submit"
              >
                Sign in
              </button>
            </form>
            <p class="mt-4 text-center small text-muted mb-0">
              New here? <a href="register.php">Create an account</a>
            </p>
          </div>
        </div>
      </div>
    </main>
    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Shared application behavior -->
    <script src="assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>



