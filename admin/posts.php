<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$category_id = 0;
$status = "";
$page = 1;
$posts_per_page = 10;

if ( isset( $_GET["category_id"] ) ) {
    $category_id = $_GET["category_id"];
}

if ( isset( $_GET["status"] ) ) {
    if (
        $_GET["status"] == "Active" ||
        $_GET["status"] == "InActive"
    ) {
        $status = $_GET["status"];
    }
}

if ( isset( $_GET["page"] ) ) {
    $page = $_GET["page"];
}

if ( $page < 1 ) {
    $page = 1;
}

$conditions = "WHERE 1";

if ( $category_id > 0 ) {
    $conditions .=
        " AND post_category.category_id = $category_id";
}

if ( $status != "" ) {
    $conditions .=
        " AND post.post_status = '$status'";
}

$sql = "SELECT COUNT(DISTINCT post.post_id) AS total_posts
FROM post
LEFT JOIN post_category
ON post.post_id = post_category.post_id
$conditions";

$result = mysqli_query(
    $conn,
    $sql
);
$row = mysqli_fetch_assoc(
    $result
);
$total_posts = $row["total_posts"];
$total_pages = 0;
$remaining_posts = $total_posts;

while ( $remaining_posts > 0 ) {
    $total_pages = $total_pages + 1;
    $remaining_posts = $remaining_posts - $posts_per_page;
}

if ( $page > $total_pages && $total_pages > 0 ) {
    $page = $total_pages;
}

$start = ( $page - 1 ) * $posts_per_page;

$sql = "SELECT post.*, category.category_title,
COUNT(post_comment.post_comment_id) AS total_comments
FROM post
LEFT JOIN post_category
ON post.post_id = post_category.post_id
LEFT JOIN category
ON post_category.category_id = category.category_id
LEFT JOIN post_comment
ON post.post_id = post_comment.post_id
$conditions
GROUP BY post.post_id
ORDER BY post.created_at DESC
LIMIT $start, $posts_per_page";

$post_result = mysqli_query(
    $conn,
    $sql
);

$post_rows = array();
$post_count = 0;

if ( $post_result != false ) {
    $post_row = mysqli_fetch_assoc(
    $post_result
);

    while ( $post_row != false ) {
        $post_rows[$post_count] = $post_row;
        $post_count++;

        $post_row = mysqli_fetch_assoc(
    $post_result
);
    }
}

$sql = "SELECT *
FROM category
ORDER BY category_title ASC";

$category_result = mysqli_query(
    $conn,
    $sql
);
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage posts | Tales</title>
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
        <!-- Posts -->

        <div class="d-flex justify-content-between">
          <div>
            <span class="eyebrow">Content</span>
            <h1>Manage posts</h1>
          </div>
          <a class="btn btn-primary" href="post_form.php"
            ><i class="bi bi-plus"></i> Create post</a
          >
        </div>

        <!-- Pagination -->

        <div class="surface p-4 mt-3">
          <form action="posts.php" method="get" class="row g-2 mb-3">
            <div class="col-md-5">
              <input
                data-table-search
                class="form-control"
                placeholder="Search visible posts"
              />
            </div>
            <div class="col-md-3">
              <select class="form-select" name="category_id">
                <option value="0">All categories</option>
                <?php
                  while ( $category = mysqli_fetch_assoc(
    $category_result
) ) {
                ?>
                  <option
                    value="<?php
                      echo $category["category_id"];
                    ?>"<?php
                      if ( $category_id == $category["category_id"] ) {
    echo " selected";
}
                    ?>>
                    <?php
                      echo $category["category_title"];
                    ?>
                  </option>
                <?php
                  }
                ?>
              </select>
            </div>
            <div class="col-md-2">
              <select class="form-select" name="status">
                <option value="">Any status</option>
                <option
                  value="Active"
                  <?php
                    if ( $status == "Active" ) {
                  ?>
                    selected
                  <?php
                    }
                  ?>
                >
                  Active
                </option>
                <option
                  value="InActive"
                  <?php
                    if ( $status == "InActive" ) {
                  ?>
                    selected
                  <?php
                    }
                  ?>
                >
                  Inactive
                </option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary w-100" type="submit">Apply Filters</button>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Comments</th>
                  <th>Status</th>
                  <th>Published</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if ( $post_count > 0 ) {
                ?>
                  <?php
                    $post_position = 0;
                    while ( isset( $post_rows[$post_position] ) ) {
                        $row = $post_rows[$post_position];
                        $post_position++;
                  ?>
                    <tr data-search-row>
                      <td><strong><?php
                        echo $row["post_title"];
                      ?></strong></td>
                      <td><?php
                        echo $row["category_title"];
                      ?></td>
                      <td>
                        <?php
                          echo $row["total_comments"];
                        ?>
                        <?php
                          if ( $row["is_comment_allowed"] == 1 ) {
                        ?>
                          &middot; allowed
                        <?php
                          } else {
                        ?>
                          &middot; off
                        <?php
                          }
                        ?>
                      </td>
                      <td>
                        <span
                          class="status <?php
                            if ( $row["post_status"] == "Active" ) {
    echo "active";
} else { echo "inactive"; }
                          ?>">
                          <?php
                            echo $row["post_status"];
                          ?>
                        </span>
                      </td>
                      <td><?php
                        echo $row["created_at"];
                      ?></td>
                      <td>
                        <a
                          class="btn btn-sm btn-outline-primary"
                          href="edit_post.php?post_id=<?php
                            echo $row["post_id"];
                          ?>">Edit</a>
                        <form action="../actions/post_status_action.php" method="post" class="d-inline">
                          <input type="hidden" name="post_id" value="<?php
                            echo $row["post_id"];
                          ?>" />
                          <?php
                            if ( $row["post_status"] == "Active" ) {
                          ?>
                            <input type="hidden" name="status" value="InActive" />
                            <button class="btn btn-sm btn-outline-danger" type="submit">Deactivate</button>
                          <?php
                            } else {
                          ?>
                            <input type="hidden" name="status" value="Active" />
                            <button class="btn btn-sm btn-primary" type="submit">Activate</button>
                          <?php
                            }
                          ?>
                        </form>
                      </td>
                    </tr>
                  <?php
                    }
                  ?>
                <?php
                  } else {
                ?>
                  <tr>
                    <td colspan="6" class="text-center">No posts found.</td>
                  </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>

          <?php
            if ( $total_pages > 1 ) {
          ?>
            <nav class="mt-4" aria-label="Post management pages">
              <ul class="pagination">
                <li class="page-item<?php
                  if ( $page == 1 ) {
    echo " disabled";
}
                ?>">
                  <a
                    class="page-link"
                    href="posts.php?page=<?php
                      echo $page - 1;
                    ?>&category_id=<?php
                      echo $category_id;
                    ?>&status=<?php
                      echo $status;
                    ?>"
                  >
                    &lsaquo;
                  </a>
                </li>
                <?php
                  for ( $number = 1; $number <= $total_pages; $number++ ) {
                ?>
                  <li class="page-item<?php
                    if ( $number == $page ) {
    echo " active";
}
                  ?>">
                    <a
                      class="page-link"
                      href="posts.php?page=<?php
                        echo $number;
                      ?>&category_id=<?php
                        echo $category_id;
                      ?>&status=<?php
                        echo $status;
                      ?>"
                    >
                      <?php
                        echo $number;
                      ?>
                    </a>
                  </li>
                <?php
                  }
                ?>
                <li class="page-item<?php
                  if ( $page == $total_pages ) {
    echo " disabled";
}
                ?>">
                  <a
                    class="page-link"
                    href="posts.php?page=<?php
                      echo $page + 1;
                    ?>&category_id=<?php
                      echo $category_id;
                    ?>&status=<?php
                      echo $status;
                    ?>"
                  >
                    &rsaquo;
                  </a>
                </li>
              </ul>
            </nav>
          <?php
            }
          ?>
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


