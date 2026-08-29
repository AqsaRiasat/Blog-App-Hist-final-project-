<?php
  require "includes/session.php";

  $forgot_field_error = "";

  if ( isset( $_SESSION["forgot_error"] ) ) {
      $forgot_field_error = $_SESSION["forgot_error"];
      $_SESSION["forgot_error"] = null;
  }
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1" />
		<title>Forgot password | Tales</title>
		<!-- Theme -->
		<link
		  href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
		  rel="stylesheet" />
		<!-- Scripts -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
		<!-- Animation -->
		<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
		<!-- Styles -->
		<link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
	</head>
	<body data-page="forgot-password">
    <?php require "includes/popup_message.php"; ?>

		<!-- Authentication -->
		<main class="auth-wrap">
			<div class="container">
				<div class="surface auth-card">
          <!-- Left side illustration panel -->
          <div class="auth-illustration-side col-md-5 d-none d-md-flex">
            <div class="auth-illustration-image-wrap">
              <img src="assets/images/auth/forgot_illustration.jpg" alt="Recover password">
            </div>
          </div>
          
          <!-- Right side form panel -->
          <div class="auth-form-side col-md-7 col-12">
            <!-- Brand title for mobile/small screen only -->
            <a class="brand h3 d-block d-md-none mb-4" href="index.php" style="color: #121212 !important; font-weight: 800;">Tales</a>
            <h2>Forgot Password</h2>
            <p class="form-subtitle">Enter the validated email address associated with your account.</p>

            <form
              action="actions/forgot_password_action.php"
              method="post"
              class="needs-validation" novalidate
            >
              <div class="mb-3">
                <label for="recovery_email" class="form-label">Email address</label>
                <input id="recovery_email" name="email" type="email" class="form-control" required />
                <?php if ( $forgot_field_error != "" ) { ?><div class="server-field-error mt-2"><?php echo $forgot_field_error; ?></div><?php } ?>
              </div>
              <button class="btn btn-primary w-100" type="submit">Email recovery instructions</button>
            </form>
            <p class="mt-4 text-center small text-muted mb-0">
              <a href="login.php" style="color: #f3ba40 !important; font-weight: 700; text-decoration: none;">Back to sign in</a>
            </p>
          </div>
				</div>
			</div>
		</main>
		<!-- Scripts -->
		<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
		<script src="assets/js/app.js?v=20260730-children-content"></script>
	</body>
</html>



