<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$sql = "SELECT *
FROM user_feedback
ORDER BY created_at DESC";

$feedback_result = mysqli_query(
    $conn,
    $sql
);

$has_feedback = false;
$feedback_rows = array();

if ( $feedback_result != false ) {
    while ( $row = mysqli_fetch_assoc(
    $feedback_result
) ) {
        $has_feedback = true;
        $feedback_rows[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Feedback | Tales</title>
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
    <!-- Styles -->
    <link href="../assets/css/styles.css?v=20260827-theme-toggle" rel="stylesheet" />
  </head>
  <body data-admin-page="feedback">
    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <span class="eyebrow">Inbox</span>
        <h1>User feedback</h1>

        <!-- Contact -->
        <div class="surface p-4">
          <input
            data-table-search
            class="form-control mb-3"
            placeholder="Search visible feedback"
          />
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Sender</th>
                  <th>Feedback</th>
                  <th>Received</th>
                </tr>
              </thead>
              <tbody>
                <?php
  if ( $has_feedback == true ) {
?>
                  <?php
  foreach ( $feedback_rows as $row ) {
?>
                    <tr data-search-row>
                      <td>
                        <strong><?php
  echo $row["user_name"];
?></strong>
                        <small class="d-block"><?php
  echo $row["user_email"];
?></small>
                      </td>
                      <td><?php
  echo $row["feedback"];
?></td>
                      <td><?php
  echo $row["created_at"];
?></td>
                    </tr>
                  <?php
  }
?>
                <?php
  } else {
?>
                  <tr>
                    <td colspan="3" class="text-center text-muted py-4">
                      No feedback found.
                    </td>
                  </tr>
                <?php
  }
?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="../assets/js/admin-components.js?v=20260803-add-blog-v2"></script>
    <!-- Shared application behavior -->
    <script src="../assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>


