<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$blog_error = "";
if ( isset( $_SESSION["blog_error"] ) ) {
    $blog_error = $_SESSION["blog_error"];
    unset( $_SESSION["blog_error"] );
}

$is_edit = false;
$blog_id = 0;
$blog_title = "";
$blog_description = "";
$post_per_page = 3;
$is_active = "Active";
$blog_background_image = "";

if ( isset( $_GET["blog_id"] ) && (int)$_GET["blog_id"] > 0 ) {
    $blog_id = (int)$_GET["blog_id"];
    $query = "SELECT * FROM blog WHERE blog_id = $blog_id";
    $result = mysqli_query( $conn, $query );
    if ( $result && mysqli_num_rows( $result ) > 0 ) {
        $b_data = mysqli_fetch_assoc( $result );
        $is_edit = true;
        $blog_title = $b_data["blog_title"];
        $post_per_page = $b_data["post_per_page"];
        $is_active = $b_data["blog_status"];
        $blog_background_image = $b_data["blog_background_image"];
    }
}

$page_title = $is_edit ? "Edit blog | Tales" : "Create blog | Tales";
$heading = $is_edit ? "Edit blog" : "Create new blog";
$button_text = $is_edit ? "Update blog" : "Create blog";
$form_action = $is_edit ? "edit" : "add";

?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo $page_title; ?></title>
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
    <!-- Styles -->
    <link href="../assets/css/styles.css?v=20260827-theme-toggle" rel="stylesheet" />
  </head>
  <body data-admin-page="blog_form">
    <?php require "../includes/popup_message.php"; ?>

    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <div class="d-flex justify-content-between align-items-end mb-3">
          <div>
            <span class="eyebrow">Blogs</span>
            <h1><?php echo $heading; ?></h1>
          </div>
          <a href="blogs.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> View all blogs
          </a>
        </div>

        <?php if ( !empty( $blog_error ) ) { ?>
          <div class="alert alert-danger mt-3"><?php echo htmlspecialchars( $blog_error ); ?></div>
        <?php } ?>

        <!-- Blogs Form -->
        <form
          action="../actions/blog_action.php"
          method="post"
          class="needs-validation mt-3"
          novalidate
          enctype="multipart/form-data"
        >
          <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
          <?php if ( $is_edit ) { ?>
            <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>" />
          <?php } ?>

          <div class="row g-4">
            <div class="col-xl-8">
              <div class="surface p-4">
                <label class="form-label" for="blog_title">Blog title *</label>
                <input
                  id="blog_title"
                  name="blog_title"
                  class="form-control form-control-lg mb-3"
                  placeholder="A clear, creative blog title"
                  value="<?php echo htmlspecialchars( $blog_title ); ?>"
                  required
                />

                <label class="form-label" for="blog_description">Blog description</label>
                <textarea
                  id="blog_description"
                  name="blog_description"
                  class="form-control mb-3"
                  rows="6"
                  placeholder="Describe what kind of stories or articles will be featured in this blog..."
                ><?php echo htmlspecialchars( $blog_description ); ?></textarea>
              </div>
            </div>

            <aside class="col-xl-4">
              <div class="surface p-4 mb-3">
                <label class="form-label" for="post_per_page">Posts per page *</label>
                <input
                  id="post_per_page"
                  name="post_per_page"
                  type="number"
                  min="1"
                  max="50"
                  value="<?php echo $post_per_page; ?>"
                  class="form-control mb-3"
                  required
                />

                <label class="form-label" for="blog_image">Blog cover image</label>
                <input
                  id="blog_image"
                  name="blog_background_image"
                  type="file"
                  accept="image/*"
                  class="form-control mb-3"
                />
                <?php if ( !empty( $blog_background_image ) ) { ?>
                  <div class="mb-3">
                    <small class="text-muted d-block mb-1">Current cover image:</small>
                    <img src="../<?php echo htmlspecialchars($blog_background_image); ?>" alt="Cover" class="img-thumbnail blog-cover-thumb" />
                  </div>
                <?php } ?>

                <div class="form-check form-switch mb-2">
                  <input
                    class="form-check-input"
                    id="blog_active"
                    name="is_active"
                    value="1"
                    type="checkbox"
                    <?php if ( $is_active == "Active" ) echo "checked"; ?>
                  />
                  <label class="form-check-label" for="blog_active">
                    Active blog
                  </label>
                </div>
              </div>

              <button class="btn btn-primary w-100" type="submit">
                <?php echo $button_text; ?>
              </button>
            </aside>
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
