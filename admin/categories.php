<?php
require "../includes/admin_auth.php";
require "../config/database.php";


$sql = "SELECT category.*, COUNT(post_category.post_id) AS total_posts
FROM category
LEFT JOIN post_category ON category.category_id = post_category.category_id
GROUP BY category.category_id
ORDER BY category.category_id DESC";

$result = mysqli_query(
    $conn,
    $sql
);
$categories = array();
if ( $result != false ) {
    while ( $row = mysqli_fetch_assoc(
    $result
) ) {
        $categories[] = $row;
    }
}

$edit_category = false;

if ( isset( $_GET["edit_id"] ) && $_GET["edit_id"] != "" ) {
    $edit_id = $_GET["edit_id"];

    $edit_id_is_valid = true;
    $edit_id_position = 0;

    if ( !isset( $edit_id[0] ) ) {
        $edit_id_is_valid = false;
    }

    while ( isset( $edit_id[$edit_id_position] ) ) {
        if ( $edit_id[$edit_id_position] < "0" || $edit_id[$edit_id_position] > "9" ) {
            $edit_id_is_valid = false;
        }
        $edit_id_position++;
    }

    if ( $edit_id_is_valid == true ) {
        $edit_sql = "SELECT * FROM category WHERE category_id = $edit_id";
        $edit_result = mysqli_query(
    $conn,
    $edit_sql
);

        if ( $edit_result != false ) {
            $edit_category = mysqli_fetch_assoc(
    $edit_result
);
        }
    }
}

/* Categories */
$form_action = "add";
$form_heading = "Add category";
$form_title = "";
$form_description = "";
$form_button_text = "Save category";

if ( $edit_category != false ) {
    $form_action = "edit";
    $form_heading = "Edit category";
    $form_title = $edit_category["category_title"];
    $form_description = $edit_category["category_description"];
    $form_button_text = "Update category";
}
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage categories | Tales</title>
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
  <body data-admin-page="categories">
    <?php
  require "../includes/popup_message.php";
?>

    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <span class="eyebrow">Taxonomy</span>
        <h1>Manage categories</h1>

        <!-- Categories -->
        <div class="row g-4 mt-2">
          <div class="col-lg-8">
            <div class="surface p-4 h-100">
              <input
                data-table-search
                class="form-control mb-3"
                placeholder="Search categories"
              />
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Posts</th>
                    <th>Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $total_cats = 0;
                    $ci = 0;
                    while ( isset( $categories[$ci] ) ) { $total_cats++; $ci++; }
                  ?>
                  <?php
  if ( $total_cats > 0 ) {
?>
                    <?php foreach ( $categories as $cat ) { 
                      $cat_status_lower = "";
                      $cs = $cat["category_status"];
                      $cs_i = 0;
                      while ( isset( $cs[$cs_i] ) ) {
                          if ( $cs[$cs_i] >= "A" && $cs[$cs_i] <= "Z" ) {
                              $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                              $lower = "abcdefghijklmnopqrstuvwxyz";
                              $ui = 0;
                              while ( isset( $upper[$ui] ) ) {
                                  if ( $upper[$ui] == $cs[$cs_i] ) { $cat_status_lower .= $lower[$ui]; break; }
                                  $ui++;
                              }
                          } else {
                              $cat_status_lower .= $cs[$cs_i];
                          }
                          $cs_i++;
                      }
                    ?>
                      <tr data-search-row>
                        <td>
                          <strong><?php
  echo $cat["category_title"];
?></strong>
                          <?php
  if ( isset( $cat["category_description"] ) && $cat["category_description"] != "" ) {
?>
                            <br/><small class="text-muted"><?php
  echo $cat["category_description"];
?></small>
                          <?php
  }
?>
                        </td>
                        <td><?php
  echo $cat["total_posts"];
?></td>
                        <td><span class="status <?php
  echo $cat_status_lower;
?>"><?php
  echo $cat["category_status"];
?></span></td>
                        <td>
                          <a class="btn btn-sm btn-outline-primary" href="categories.php?edit_id=<?php
  echo $cat["category_id"];
?>">Edit</a>
                          <form action="../actions/category_action.php" method="post" class="d-inline">
                            <input type="hidden" name="action" value="toggle_status" />
                            <input type="hidden" name="category_id" value="<?php
  echo $cat["category_id"];
?>" />
                            <?php
  if ( $cat["category_status"] == "Active" ) {
?>
                              <input type="hidden" name="status" value="InActive" />
                              <button class="btn btn-sm btn-outline-danger" type="submit">Deactivate</button>
                            <?php
  } else {
?>
                              <input type="hidden" name="status" value="Active" />
                              <button class="btn btn-sm btn-outline-success" type="submit">Activate</button>
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
                      <td colspan="4" class="text-muted">No categories found.</td>
                    </tr>
                  <?php
  }
?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-lg-4">
            <!-- Categories -->
            <form action="../actions/category_action.php" method="post" class="surface p-4 h-100 needs-validation" novalidate>
              <input type="hidden" name="action" value="<?php
  echo $form_action;
?>" />
              <?php
  if ( $edit_category ) {
?>
                <input type="hidden" name="category_id" value="<?php
  echo $edit_category["category_id"];
?>" />
              <?php
  }
?>
              <h2 class="h4"><?php
  echo $form_heading;
?></h2>
              <label class="form-label" for="category_name">Category title *</label>
              <input
                id="category_name"
                name="title"
                class="form-control mb-3"
                value="<?php
  echo $form_title;
?>"
                required
              />
              <label class="form-label" for="category_description">Description</label>
              <textarea
                id="category_description"
                name="description"
                class="form-control mb-3"
                rows="4"
              ><?php
  echo $form_description;
?></textarea>
              <div class="form-check form-switch mb-3">
                <input
                  id="category_active"
                  name="is_active"
                  value="1"
                  class="form-check-input"
                  type="checkbox"
                  <?php
                    if ( !$edit_category || $edit_category["category_status"] == "Active" ) {
                        echo "checked";
                    }
                  ?>
                />
                <label for="category_active">Active category</label>
              </div>
              <button class="btn btn-primary" type="submit">
                <?php
  echo $form_button_text;
?>
              </button>
              <?php
  if ( $edit_category ) {
?>
                <a class="btn btn-outline-primary ms-2" href="categories.php">Cancel</a>
              <?php
  }
?>
            </form>
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






