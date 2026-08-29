<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$sql = "SELECT *
FROM blog
WHERE blog_status = 'Active'
ORDER BY blog_id ASC
LIMIT 1";

$result = mysqli_query(
    $conn,
    $sql
);
$blog = mysqli_fetch_assoc(
    $result
);

$sql = "SELECT *
FROM category
WHERE category_status = 'Active'
ORDER BY category_title ASC";

$category_result = mysqli_query(
    $conn,
    $sql
);

$categories = array();
if ( $category_result != false ) {
    while ( $row = mysqli_fetch_assoc(
    $category_result
) ) {
        $categories[] = $row;
    }
}

$blog_id = "";

if (
    $blog != false &&
    isset( $blog["blog_id"] )
) {
    $blog_id = $blog["blog_id"];
}

$post_error = "";
if ( isset( $_SESSION["post_error"] ) ) {
    $post_error = $_SESSION["post_error"];
    unset( $_SESSION["post_error"] );
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Create post | Tales</title>
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
  <body data-admin-page="posts">
    <?php
  require "../includes/popup_message.php";
?>

    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <span class="eyebrow">Posts</span>
        <h1>Create new post</h1>

        <!-- Posts -->
        <form
          action="../actions/create_post_action.php"
          method="post"
          class="needs-validation mt-3"
          novalidate
          enctype="multipart/form-data"
        >
          <input
            type="hidden"
            name="blog_id"
            value="<?php
  echo $blog_id;
?>"
          />
          <div class="row g-4">
            <div class="col-xl-8">
              <div class="surface p-4">
                <label class="form-label" for="post_title">Post title *</label>
                <input
                  id="post_title"
                  name="title"
                  class="form-control form-control-lg mb-3"
                  placeholder="A clear, useful title"
                  required
                />

                <label class="form-label" for="post_excerpt">Short description *</label>
                <textarea
                  id="post_excerpt"
                  name="short_description"
                  class="form-control mb-3"
                  rows="3"
                  required
                ></textarea>

                <label class="form-label" for="post_content">Post content *</label>
                <div class="btn-group mb-2" aria-label="Editor tools">
                  <button type="button" class="btn btn-outline-secondary">
                    <b>B</b>
                  </button>
                  <button type="button" class="btn btn-outline-secondary">
                    <i>I</i>
                  </button>
                  <button type="button" class="btn btn-outline-secondary">
                    Link
                  </button>
                </div>
                <textarea
                  id="post_content"
                  name="content"
                  class="form-control"
                  rows="16"
                  required
                ></textarea>
              </div>
            </div>

            <aside class="col-xl-4">
              <div class="surface p-4 mb-3">
                <label class="form-label" for="post_category">Category *</label>
                <select
                  id="post_category"
                  name="category_id"
                  class="form-select mb-3"
                  required
                >
                  <option value="">Choose category</option>
                  <?php
  foreach ( $categories as $category ) {
?>
                    <option value="<?php
  echo $category['category_id'];
?>">
                      <?php
  echo $category['category_title'];
?>
                    </option>
                  <?php
  }
?>
                </select>

                <label class="form-label" for="post_image">Story Image *</label>
                <input
                  id="post_image"
                  name="image"
                  type="file"
                  accept="image/*"
                  class="form-control mb-3"
                  required
                />

                <label class="form-label" for="publish_date">Publish date</label>
                <input
                  id="publish_date"
                  name="publish_date"
                  type="date"
                  class="form-control mb-3"
                />

                <div class="form-check form-switch mb-2">
                  <input
                    class="form-check-input"
                    id="allow_comments"
                    name="allow_comments"
                    value="1"
                    type="checkbox"
                    checked
                  />
                  <label class="form-check-label" for="allow_comments">
                    Allow discussion
                  </label>
                </div>

                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    id="post_active"
                    name="is_active"
                    value="1"
                    type="checkbox"
                    checked
                  />
                  <label class="form-check-label" for="post_active">
                    Active post
                  </label>
                </div>
              </div>

              <button class="btn btn-primary w-100" type="submit">
                Create post
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


