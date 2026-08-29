<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$status = "";

if ( isset( $_GET["status"] ) ) {
    if (
        $_GET["status"] == "Active" ||
        $_GET["status"] == "InActive"
    ) {
        $status = $_GET["status"];
    }
}

$conditions = "";

if ( $status != "" ) {
    $conditions = "WHERE post_comment.is_active = '$status'";
}

$sql = "SELECT post_comment.*, user.first_name,
user.last_name, post.post_title
FROM post_comment
INNER JOIN user
ON post_comment.user_id = user.user_id
INNER JOIN post
ON post_comment.post_id = post.post_id
$conditions
ORDER BY post_comment.created_at DESC";

$comment_result = mysqli_query(
    $conn,
    $sql
);

$comment_rows = array();
$comment_count = 0;

if ( $comment_result != false ) {
    $comment_row = mysqli_fetch_assoc(
    $comment_result
);

    while ( $comment_row != false ) {
        $comment_rows[$comment_count] = $comment_row;
        $comment_count++;

        $comment_row = mysqli_fetch_assoc(
    $comment_result
);
    }
}

$user_sql = "SELECT user_id, first_name, last_name
FROM user
WHERE is_active = 'Active'
AND is_approved = 'Approved'
ORDER BY first_name ASC";
$user_result = mysqli_query( $conn, $user_sql );

$post_sql = "SELECT post_id, post_title
FROM post
WHERE post_status = 'Active'
ORDER BY post_title ASC";
$post_result = mysqli_query( $conn, $post_sql );
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage comments | Tales</title>
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
  <body data-admin-page="comments">
    <?php
  require "../includes/popup_message.php";
?>

    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <span class="eyebrow">Discussion</span>
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3"><h1 class="mb-0">Manage comments</h1><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addCommentModal"><i class="bi bi-plus-lg"></i> Add comment</button></div>

        <!-- Search -->

        <div class="surface p-4">
          <form action="comments.php" method="get" class="row g-2 mb-3">
            <div class="col-md-8">
              <input
                data-table-search
                class="form-control"
                placeholder="Search visible comments, users, or posts"
              />
            </div>
            <div class="col-md-2">
              <select class="form-select" name="status">
                <option value="">All statuses</option>
                <option value="InActive"<?php
                  if ( $status == "InActive" ) {
    echo " selected";
}
                ?>>Pending</option>
                <option value="Active"<?php
                  if ( $status == "Active" ) {
    echo " selected";
}
                ?>>Active</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary w-100" type="submit">Apply Filter</button>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>User / post</th>
                  <th>Comment</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if ( $comment_count > 0 ) {
                ?>
                  <?php
                    $comment_position = 0;
                    while ( isset( $comment_rows[$comment_position] ) ) {
                        $row = $comment_rows[$comment_position];
                        $comment_position++;
                  ?>
                    <tr data-search-row>
                      <td>
                        <strong><?php
                          echo $row["first_name"] . " " . $row["last_name"];
                        ?></strong>
                        <small class="d-block text-muted"><?php
                          echo $row["post_title"];
                        ?></small>
                      </td>
                      <td><?php
                        echo $row["comment"];
                      ?></td>
                      <td>
                        <?php
                          if ( $row["is_active"] == "Active" ) {
                        ?>
                          <span class="status active">Active</span>
                        <?php
                          } else {
                        ?>
                          <span class="status pending">Pending</span>
                        <?php
                          }
                        ?>
                      </td>
                      <td>
                        <button
                          class="btn btn-sm btn-outline-primary"
                          type="button"
                          data-bs-toggle="modal"
                          data-bs-target="#editCommentModal"
                          data-edit-comment
                          data-comment-id="<?php
  echo $row["post_comment_id"];
?>"
                          data-comment-text="<?php
  echo $row["comment"];
?>"
                          data-comment-status="<?php
  echo $row["is_active"];
?>"
                        >Edit</button>
                        <form action="../actions/comment_status_action.php" method="post" class="d-inline">
                          <input type="hidden" name="comment_id" value="<?php
                            echo $row["post_comment_id"];
                          ?>" />
                          <?php
                            if ( $row["is_active"] == "Active" ) {
                          ?>
                            <input type="hidden" name="status" value="InActive" />
                            <button class="btn btn-sm btn-outline-danger" type="submit">Deactivate</button>
                          <?php
                            } else {
                          ?>
                            <input type="hidden" name="status" value="Active" />
                            <button class="btn btn-sm btn-primary" type="submit">Approve</button>
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
                    <td colspan="4" class="text-center">No comments found.</td>
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
    <!-- Comments -->
    <div class="modal fade" id="addCommentModal" tabindex="-1" aria-labelledby="addCommentTitle" aria-hidden="true">
      <div class="modal-dialog">
        <form action="../actions/add_comment_action.php" method="post" class="modal-content needs-validation" novalidate>
          <div class="modal-header">
            <h2 class="modal-title h4" id="addCommentTitle">Add comment</h2>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="comment_user_id">User</label>
              <select class="form-select" id="comment_user_id" name="user_id" required>
                <option value="">Choose user</option>
                <?php while ( $user_row = mysqli_fetch_assoc( $user_result ) ) { ?>
                  <option value="<?php echo $user_row["user_id"]; ?>"><?php echo $user_row["first_name"] . " " . $user_row["last_name"]; ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">Select a user.</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="comment_post_id">Post</label>
              <select class="form-select" id="comment_post_id" name="post_id" required>
                <option value="">Choose post</option>
                <?php while ( $post_row = mysqli_fetch_assoc( $post_result ) ) { ?>
                  <option value="<?php echo $post_row["post_id"]; ?>"><?php echo $post_row["post_title"]; ?></option>
                <?php } ?>
              </select>
              <div class="invalid-feedback">Select a post.</div>
            </div>
            <div class="mb-3">
              <label class="form-label" for="new_comment">Comment</label>
              <textarea class="form-control" id="new_comment" name="comment" rows="5" required></textarea>
              <div class="invalid-feedback">Enter a comment.</div>
            </div>
            <div>
              <label class="form-label" for="new_comment_status">Status</label>
              <select class="form-select" id="new_comment_status" name="status">
                <option value="InActive">Pending</option>
                <option value="Active">Active</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Add comment</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Comments -->

    <div
      class="modal fade"
      id="editCommentModal"
      tabindex="-1"
      aria-labelledby="editCommentTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <form action="../actions/update_comment_action.php" method="post" class="modal-content needs-validation" novalidate>
          <input type="hidden" name="comment_id" value="" />
          <div class="modal-header">
            <h2 class="modal-title h4" id="editCommentTitle">Update comment</h2>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <label class="form-label" for="edit_comment_text">Comment *</label
            ><textarea
              id="edit_comment_text"
              name="comment"
              class="form-control mb-3"
              rows="5"
              required
            ></textarea
            >
            <div class="invalid-feedback">Comment text is required.</div>
            <label class="form-label" for="edit_comment_status"
              >Comment status *</label
            ><select
              id="edit_comment_status"
              name="status"
              class="form-select"
              required
            >
              <option value="Active">Active</option>
              <option value="InActive">Inactive</option>
            </select>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-primary"
              data-bs-dismiss="modal"
            >
              Cancel</button
            ><button
              class="btn btn-primary"
              type="submit"
            >
              Update comment
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="../assets/js/admin-components.js?v=20260803-add-blog-v2"></script>
    <!-- Shared application behavior -->
    <script src="../assets/js/app.js?v=20260730-children-content"></script>
  </body>
</html>





