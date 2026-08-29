<?php
require "../includes/admin_auth.php";
require "../config/database.php";

$blog = array(
    "blog_id" => "",
    "blog_title" => "",
    "post_per_page" => "3",
    "blog_background_image" => ""
);

$blog_result = mysqli_query( $conn, "SELECT * FROM blog ORDER BY blog_id ASC LIMIT 1" );
if ( $blog_result != false ) {
    $blog_row = mysqli_fetch_assoc(
    $blog_result
);
    if ( $blog_row ) {
        $blog = $blog_row;
    }
}

$settings = array(
    "blog_description" => "",
    "post_title_color" => "#ffffff",
    "post_background_color" => "#17151c",
    "font_style" => "DM Sans"
);

$settings_result = mysqli_query( $conn, "SELECT setting_key, setting_value FROM setting WHERE user_id = " . $_SESSION["user_id"] . " AND setting_status = 'Active'" );
if ( $settings_result != false ) {
    while ( $setting_row = mysqli_fetch_assoc(
    $settings_result
) ) {
        if ( isset( $settings[$setting_row["setting_key"]] ) ) {
            $settings[$setting_row["setting_key"]] = $setting_row["setting_value"];
        }
    }
}
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Blog settings | Tales</title>
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
  <body data-admin-page="settings">
    <?php
  require "../includes/popup_message.php";
?>
    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <span class="eyebrow">Appearance & behavior</span>
        <h1>Blog settings</h1>
        <div id="demo-alert" hidden></div>
        <!-- Forms -->

        <form action="../actions/settings_action.php" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
          <input type="hidden" name="blog_id" value="<?php
  echo $blog["blog_id"];
?>" />
          <div class="row g-4">
            <div class="col-xl-7">
              <div class="surface p-4">
                <h2 class="h4">General settings</h2>
                <label class="form-label" for="blog_title">Blog title *</label
                ><input
                  id="blog_title"
                  name="blog_title"
                  class="form-control mb-3"
                  value="<?php
  echo $blog["blog_title"];
?>"
                  required
                /><label class="form-label" for="blog_description"
                  >Blog description</label
                ><textarea
                  id="blog_description"
                  name="blog_description"
                  class="form-control mb-3"
                  rows="3"
                ><?php
  echo $settings["blog_description"];
?></textarea
                ><label class="form-label" for="posts_per_page"
                  >Posts per page *</label
                ><input
                  id="posts_per_page"
                  name="posts_per_page"
                  type="number"
                  min="1"
                  max="50"
                  value="<?php
  echo $blog["post_per_page"];
?>"
                  class="form-control mb-3"
                  required
                /><label class="form-label" for="background_image"
                  >Blog page background image</label
                ><input
                  id="background_image"
                  name="background_image"
                  type="file"
                  accept="image/*"
                  class="form-control"
                />
              </div>
            </div>
            <div class="col-xl-5">
              <div class="surface p-4">
                <h2 class="h4">Post appearance</h2>
                <label class="form-label" for="post_title_color"
                  >Post title color</label
                ><input
                  id="post_title_color"
                  name="post_title_color"
                  type="color"
                  value="<?php
  echo $settings["post_title_color"];
?>"
                  class="form-control form-control-color mb-3"
                /><label class="form-label" for="post_background_color"
                  >Post background color</label
                ><input
                  id="post_background_color"
                  name="post_background_color"
                  type="color"
                  value="<?php
  echo $settings["post_background_color"];
?>"
                  class="form-control form-control-color mb-3"
                /><label class="form-label" for="font_style">Font style</label
                ><select id="font_style" name="font_style" class="form-select">
                  <option<?php
  if ( $settings["font_style"] == "Source Serif 4" ) {
    echo " selected";
}
?>>Source Serif 4</option>
                  <option<?php
  if ( $settings["font_style"] == "Georgia" ) {
    echo " selected";
}
?>>Georgia</option>
                  <option<?php
  if ( $settings["font_style"] == "DM Sans" ) {
    echo " selected";
}
?>>DM Sans</option>
                </select>
              </div>
            </div>
            <div class="col-12">
              <button
                class="btn btn-primary"
                type="submit"
              >
                Save settings
              </button>
            </div>
          </div>
        </form>
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






