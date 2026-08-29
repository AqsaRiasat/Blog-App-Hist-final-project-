<?php

require "includes/session.php";

$register_error_field = "";
$register_error_message = "";

if ( isset( $_SESSION["register_error"] ) ) {
    $register_error_message = $_SESSION["register_error"];

    if ( $register_error_message == "Email addresses do not match." ) { $register_error_field = "confirm_email"; }
    else if ( $register_error_message == "Passwords do not match." ) { $register_error_field = "confirm_password"; }
    else if ( $register_error_message == "Password must contain at least 8 characters, one letter and one number." ) { $register_error_field = "password"; }
    else if ( $register_error_message == "Select a valid gender." ) { $register_error_field = "gender"; }
    else if ( $register_error_message == "Enter a valid email address." || $register_error_message == "An account already exists with this email." ) { $register_error_field = "email"; }
    else if ( $register_error_message == "Profile image is required." || $register_error_message == "Profile image must not be larger than 1 MB." || $register_error_message == "Only JPG, JPEG, PNG and WebP images are allowed." ) { $register_error_field = "image"; }
    else if ( $register_error_message == "Confirm that the information is accurate." ) { $register_error_field = "terms"; }

    if ( $register_error_field != "" ) {
        $_SESSION["register_error"] = null;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Create account | Tales</title>
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
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
  </head>
  <body data-page="register">
    <?php require "includes/popup_message.php"; ?>

    <!-- Content -->
    <!-- Forms -->

    <main class="auth-wrap">
      <div class="container">
        <div class="surface auth-card">
          <!-- Left side illustration panel -->
          <div class="auth-illustration-side col-lg-4 col-md-5 d-none d-md-flex">
            <div class="auth-illustration-image-wrap">
              <img src="assets/images/auth/register_illustration.jpg" alt="Join Tales">
            </div>
          </div>
          
          <!-- Right side form panel -->
          <div class="auth-form-side col-lg-8 col-md-7 col-12">
            <!-- Brand title for mobile/small screen only -->
            <a class="brand h3 d-block d-md-none mb-3" href="index.php" style="color: #121212 !important; font-weight: 800;">Tales</a>
            <h2>Create account</h2>
            <p class="form-subtitle">Your request will be reviewed by an administrator. Required fields are marked *.</p>
            
            <form
              class="needs-validation"
              novalidate
              method="post"
              action="actions/register_action.php"
              enctype="multipart/form-data"
            >
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="first_name">First name *</label
                  ><input
                    id="first_name"
                    name="first_name"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">First name is required.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="last_name">Last name *</label
                  ><input
                    id="last_name"
                    name="last_name"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">Last name is required.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="email">Email *</label
                  ><input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">Enter a valid email address.</div><?php if ( $register_error_field == "email" ) { ?><div class="server-field-error"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="confirm_email"
                    >Confirm email *</label
                  ><input
                    id="confirm_email"
                    name="confirm_email"
                    type="email"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">Email addresses must match.</div><?php if ( $register_error_field == "confirm_email" ) { ?><div class="server-field-error"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="password">Password *</label
                  ><input
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"
                    class="form-control"
                    required
                  />
                  <div class="form-text">
                    At least 8 characters with a letter and number.
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="confirm_password"
                    >Confirm password *</label
                  ><input
                    id="confirm_password"
                    name="confirm_password"
                    type="password"
                    minlength="8"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">Passwords must match.</div><?php if ( $register_error_field == "confirm_password" ) { ?><div class="server-field-error"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-md-4">
                  <label class="form-label" for="gender">Gender *</label
                  ><select id="gender" name="gender" class="form-select" required>
                    <option value="">Choose&hellip;</option>
                    <option value="Female">Female</option>
                    <option value="Male">Male</option>
                  </select><?php if ( $register_error_field == "gender" ) { ?><div class="server-field-error"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-md-4">
                  <label class="form-label" for="date_of_birth"
                    >Date of birth *</label
                  ><input
                    id="date_of_birth"
                    name="date_of_birth"
                    type="date"
                    class="form-control"
                    required
                  />
                </div>
                <div class="col-md-4">
                  <label class="form-label" for="home_town">Home town *</label
                  ><input
                    id="home_town"
                    name="home_town"
                    class="form-control"
                    required
                  />
                </div>
                <div class="col-12">
                  <label class="form-label" for="image">Profile image *</label
                  ><input
                    id="image"
                    name="image"
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    data-max-size="1048576"
                    class="form-control"
                    required
                  />
                  <div class="invalid-feedback">
                    Choose an image no larger than 1 MB.
                  </div>
                  <div class="form-text">JPG, PNG, or WebP. Maximum 1 MB.</div><?php if ( $register_error_field == "image" ) { ?><div class="server-field-error"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-12 my-2">
                  <label class="form-label d-flex align-items-center gap-2" style="font-weight: 500 !important; cursor: pointer;">
                    <input name="terms" type="checkbox" required style="width: auto !important; margin: 0 !important;" />
                    <span>I confirm that the information is accurate.</span>
                  </label>
                  <?php if ( $register_error_field == "terms" ) { ?><div class="server-field-error mt-1"><?php echo $register_error_message; ?></div><?php } ?>
                </div>
                <div class="col-12">
                  <button
                    class="btn btn-primary w-100"
                    type="submit"
                  >
                    Submit account request
                  </button>
                  <p class="mt-3 text-center small text-muted mb-0">
                    Already registered? <a href="login.php" style="color: #f3ba40 !important; font-weight: 700; text-decoration: none;">Sign in</a>
                  </p>
                </div>
              </div>
            </form>
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



