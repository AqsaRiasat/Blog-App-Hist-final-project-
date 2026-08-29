<?php
require "../includes/admin_auth.php";
require "../config/database.php";

$sql = "SELECT blog.*, COUNT(post.post_id) AS total_posts 
        FROM blog 
        LEFT JOIN post ON blog.blog_id = post.blog_id 
        GROUP BY blog.blog_id 
        ORDER BY blog.blog_id DESC";

$result = mysqli_query( $conn, $sql );
$blogs = array();
if ( $result != false ) {
    while ( $row = mysqli_fetch_assoc( $result ) ) {
        $blogs[] = $row;
    }
}
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage blogs | Tales</title>
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
  <body data-admin-page="blogs">
    <?php require "../includes/popup_message.php"; ?>
    
    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <div class="d-flex justify-content-between align-items-end mb-4">
          <div>
            <span class="eyebrow">Blogs</span>
            <h1>Manage blogs</h1>
          </div>
          <a href="blog_form.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Add new blog
          </a>
        </div>

        <div class="surface p-4">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Cover</th>
                  <th>Blog Title</th>
                  <th>Posts</th>
                  <th>Per Page</th>
                  <th>Status</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if ( !empty( $blogs ) ) { ?>
                  <?php foreach ( $blogs as $b ) { ?>
                    <tr>
                      <td>#<?php echo $b["blog_id"]; ?></td>
                      <td>
                        <?php if ( !empty( $b["blog_background_image"] ) ) { ?>
                          <img src="../<?php echo htmlspecialchars($b["blog_background_image"]); ?>" alt="Cover" class="rounded blog-table-thumb" />
                        <?php } else { ?>
                          <span class="badge bg-secondary">No cover</span>
                        <?php } ?>
                      </td>
                      <td>
                        <strong><?php echo htmlspecialchars($b["blog_title"]); ?></strong>
                      </td>
                      <td>
                        <span class="badge bg-secondary"><?php echo $b["total_posts"]; ?> posts</span>
                      </td>
                      <td><?php echo $b["post_per_page"]; ?></td>
                      <td>
                        <?php if ( $b["blog_status"] == "Active" ) { ?>
                          <span class="status active">Active</span>
                        <?php } else { ?>
                          <span class="status inactive">Inactive</span>
                        <?php } ?>
                      </td>
                      <td class="text-end">
                        <div class="d-inline-flex gap-2">
                          <a href="blog_form.php?blog_id=<?php echo $b["blog_id"]; ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                            <i class="bi bi-pencil me-1"></i>Edit
                          </a>

                          <form action="../actions/blog_action.php" method="post" class="d-inline">
                            <input type="hidden" name="action" value="toggle_status" />
                            <input type="hidden" name="blog_id" value="<?php echo $b["blog_id"]; ?>" />
                            <input type="hidden" name="current_status" value="<?php echo $b["blog_status"]; ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Toggle Status">
                              <i class="bi bi-arrow-repeat me-1"></i>Toggle
                            </button>
                          </form>

                          <form action="../actions/blog_action.php" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog?');">
                            <input type="hidden" name="action" value="delete" />
                            <input type="hidden" name="blog_id" value="<?php echo $b["blog_id"]; ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                              <i class="bi bi-trash me-1"></i>Delete
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td colspan="7" class="text-center text-muted">No blogs found. <a href="blog_form.php">Create your first blog</a>.</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/admin-components.js?v=20260803-add-blog-v2"></script>
    <script src="../assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>
