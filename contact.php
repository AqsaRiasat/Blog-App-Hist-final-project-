<?php
  require "includes/session.php";

  $feedback_error_field = "";
  $feedback_error_message = "";

  if ( isset( $_SESSION["feedback_error"] ) ) {
      $feedback_error_message = $_SESSION["feedback_error"];

      if ( $feedback_error_message == "Enter a valid email address." ) {
          $feedback_error_field = "email";
          $_SESSION["feedback_error"] = null;
      }
  }
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Contact us | Tales</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
  </head>
  <body data-page="contact">
    <?php require "includes/popup_message.php"; ?>

    <!-- Navigation -->
    <div data-public-nav></div>
    <!-- Content -->
    <!-- Contact -->

    <main class="section">
      <div class="container">
        <div class="row g-5">
          <div
            class="col-lg-5 feedback-copy"
            data-aos="fade-right"
            data-aos-duration="1200"
            data-aos-offset="100"
          >
            <h1>Send a note</h1>
            <p class="text-muted">
              Share your thoughts, story suggestions, or questions with the Tales
              editorial team.
            </p>
            <p
              class="contact-detail"><span
              class="contact-detail-icon"><i
              class="bi bi-envelope"></i></span><span><strong>Email</strong><br />hello@wonderwood.example</span></p>
            <p
              class="contact-detail"><span
              class="contact-detail-icon"><i
              class="bi bi-geo-alt"></i></span><span><strong>Editorial office</strong><br />Hyderabad, Sindh</span></p>
          </div>
          <div class="col-lg-7">
            <div
              class="surface feedback-glass-card p-4 p-md-5"
              data-aos="fade-left"
              data-aos-duration="1200"
              data-aos-delay="150"
              data-aos-offset="100"
            >

              <form action="actions/feedback_action.php" method="post" class="needs-validation" novalidate>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label for="feedback_name" class="form-label"
                      >Full name</label
                    ><input
                      id="feedback_name"
                      name="name"
                      class="form-control"
                      required
                    />
                  </div>
                  <div class="col-md-6">
                    <label for="feedback_email" class="form-label"
                      >Email address</label
                    ><input
                      id="feedback_email"
                      name="email"
                      type="email"
                      class="form-control"
                      required
                    /><?php if ( $feedback_error_field == "email" ) { ?><div class="server-field-error"><?php echo $feedback_error_message; ?></div><?php } ?>
                  </div>
                  <div class="col-12">
                    <label for="feedback_message" class="form-label"
                      >Feedback</label
                    ><textarea
                      id="feedback_message"
                      name="message"
                      class="form-control"
                      rows="6"
                      required
                    ></textarea>
                  </div>
                  <div>
                    <button class="btn btn-primary" type="submit">
                      Send feedback
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Footer -->
    <div data-footer></div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="assets/js/components.js?v=20260730-tales-brand"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <!-- Shared application behavior -->
    <script src="assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>



