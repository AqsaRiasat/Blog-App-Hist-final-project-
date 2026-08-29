<?php

require "../includes/admin_auth.php";
require "../config/database.php";

$search = "";
if ( isset( $_GET["search"] ) ) {
    $search = $_GET["search"];
}

$user_cond = "WHERE role_id = 2";
$clean_search = "";

if ( $search != "" ) {
    $search_position = 0;

    while ( isset( $search[$search_position] ) ) {
        $search_character = $search[$search_position];

        if ( $search_character == "'" ) {
            $clean_search .= "''";
        } else if ( $search_character == "\\" ) {
            $clean_search .= "\\\\";
        } else {
            $clean_search .= $search_character;
        }

        $search_position++;
    }

    $user_cond .= " AND (
        first_name LIKE '%$clean_search%'
        OR last_name LIKE '%$clean_search%'
        OR email LIKE '%$clean_search%'
    )";
}

$sql = "SELECT * FROM user $user_cond ORDER BY user_id DESC";

$result = mysqli_query(
    $conn,
    $sql
);




$has_users = false;
$user_rows = array();

if ( $result != false ) {
    while ( $row = mysqli_fetch_assoc(
    $result
) ) {
        $has_users = true;
        $user_rows[] = $row;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Manage users | Tales</title>
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
  <body data-admin-page="users">
    <?php
  require "../includes/popup_message.php";
?>

    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <div class="d-flex flex-wrap justify-content-between">
          <div>
            <span class="eyebrow">Accounts</span>
            <h1>Manage users</h1>
          </div>
          <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#userModal"
          >
            <i class="bi bi-person-plus"></i> Add user
          </button>
        </div>

        <!-- Search -->
        <div class="surface p-3 p-md-4 mt-3">
          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <input
                class="form-control"
                data-table-search
                placeholder="Search by name or email"
                aria-label="Search users"
              />
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option>All approval states</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option>All account states</option>
                <option>Active</option>
                <option>Inactive</option>
              </select>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Email</th>
                  <th>Approval</th>
                  <th>Account</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
  if ( $has_users == true ) {
?>
                  <?php foreach ( $user_rows as $row ) { 
                    
                    
                    $approved_val = $row["is_approved"];
                    $approved_lower = "";
                    $app_len = 0;
                    while ( isset( $approved_val[$app_len] ) ) { $app_len++; }
                    for ( $i = 0; $i < $app_len; $i++ ) {
                      $c = $approved_val[$i];
                      if ( $c >= 'A' && $c <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $c ) {
                            $approved_lower .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $approved_lower .= $c;
                }
                    }

                    $active_val = $row["is_active"];
                    $active_lower = "";
                    $act_len = 0;
                    while ( isset( $active_val[$act_len] ) ) { $act_len++; }
                    for ( $i = 0; $i < $act_len; $i++ ) {
                      $c = $active_val[$i];
                      if ( $c >= 'A' && $c <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $c ) {
                            $active_lower .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $active_lower .= $c;
                }
                    }
                  ?>
                    <tr data-search-row>
                      <td>
                        <div class="author-row">
                          <img
                            class="avatar"
                            src="../<?php
  echo $row["user_image"];
?>"
                            alt=""
                          />
                          <?php
  echo $row["first_name"] . " " . $row["last_name"];
?>
                        </div>
                      </td>
                      <td><?php
  echo $row["email"];
?></td>
                      <td>
                        <span class="status <?php
  echo $approved_lower;
?>">
                          <?php
  echo $row["is_approved"];
?>
                        </span>
                      </td>
                      <td>
                        <span class="status <?php
  echo $active_lower;
?>">
                          <?php
  echo $row["is_active"];
?>
                        </span>
                      </td>
                      <td>
                        <button
                          class="btn btn-sm btn-outline-primary"
                          type="button"
                          data-bs-toggle="modal"
                          data-bs-target="#editUserModal"
                          data-edit-user
                          data-user-id="<?php
  echo $row["user_id"];
?>"
                          data-first-name="<?php
  echo $row["first_name"];
?>"
                          data-last-name="<?php
  echo $row["last_name"];
?>"
                          data-email="<?php
  echo $row["email"];
?>"
                          data-gender="<?php
  echo $row["gender"];
?>"
                          data-date-of-birth="<?php
  echo $row["date_of_birth"];
?>"
                          data-home-town="<?php
  echo $row["address"];
?>"
                          data-account-status="<?php
  echo $row["is_active"];
?>"
                        >
                          Edit
                        </button>
                        <?php
  if ( $row["is_approved"] == "Pending" ) {
?>
                          <form
                            action="../actions/user_status_action.php"
                            method="post"
                            class="d-inline"
                          >
                            <input
                              type="hidden"
                              name="user_id"
                              value="<?php
  echo $row["user_id"];
?>"
                            />
                            <button
                              class="btn btn-sm btn-primary"
                              type="submit"
                              name="action"
                              value="approve"
                            >
                              Approve
                            </button>
                            <button
                              class="btn btn-sm btn-outline-danger"
                              type="submit"
                              name="action"
                              value="reject"
                            >
                              Reject
                            </button>
                          </form>
                        <?php
  } else if ( $row["is_active"] == "Active" ) {
?>
                          <form
                            action="../actions/user_status_action.php"
                            method="post"
                            class="d-inline"
                          >
                            <input
                              type="hidden"
                              name="user_id"
                              value="<?php
  echo $row["user_id"];
?>"
                            />
                            <button
                              class="btn btn-sm btn-outline-danger"
                              type="submit"
                              name="action"
                              value="deactivate"
                            >
                              Deactivate
                            </button>
                          </form>
                        <?php
  } else if ( $row["is_approved"] == "Approved" ) {
?>
                          <form
                            action="../actions/user_status_action.php"
                            method="post"
                            class="d-inline"
                          >
                            <input
                              type="hidden"
                              name="user_id"
                              value="<?php
  echo $row["user_id"];
?>"
                            />
                            <button
                              class="btn btn-sm btn-primary"
                              type="submit"
                              name="action"
                              value="activate"
                            >
                              Activate
                            </button>
                          </form>
                        <?php
  } else {
?>
                          <span>&mdash;</span>
                        <?php
  }
?>
                      </td>
                    </tr>
                  <?php
  }
?>
                <?php
  } else {
?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                      No registered users found.
                    </td>
                  </tr>
                <?php
  }
?>
              </tbody>
            </table>
          </div>
          <nav>
            <ul class="pagination">
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
            </ul>
          </nav>
        </div>
      </main>
    </div>

    <!-- Profile -->
    <div
      class="modal fade"
      id="userModal"
      tabindex="-1"
      aria-labelledby="addUserTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form
          action="../actions/admin_add_user_action.php"
          method="post"
          class="modal-content needs-validation"
          novalidate
          enctype="multipart/form-data"
        >
          <div class="modal-header">
            <h2 class="modal-title h4" id="addUserTitle">Add user</h2>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="add_first_name">First name *</label>
                <input
                  id="add_first_name"
                  name="first_name"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">First name is required.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="add_last_name">Last name *</label>
                <input
                  id="add_last_name"
                  name="last_name"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Last name is required.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="add_email">Email *</label>
                <input
                  id="add_email"
                  name="email"
                  type="email"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Enter a valid email.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="add_confirm_email">Confirm email *</label>
                <input
                  id="add_confirm_email"
                  name="confirm_email"
                  type="email"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Email addresses must match.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="add_password">Password *</label>
                <input
                  id="add_password"
                  name="password"
                  type="password"
                  minlength="8"
                  pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">
                  Use at least 8 characters with a letter and number.
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="add_confirm_password">Confirm password *</label>
                <input
                  id="add_confirm_password"
                  name="confirm_password"
                  type="password"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Passwords must match.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="add_gender">Gender *</label>
                <select
                  id="add_gender"
                  name="gender"
                  class="form-select"
                  required
                >
                  <option value="">Choose&hellip;</option>
                  <option value="female">Female</option>
                  <option value="male">Male</option>
                  <option value="other">Other</option>
                </select>
                <div class="invalid-feedback">Select gender.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="add_date_of_birth">Date of birth *</label>
                <input
                  id="add_date_of_birth"
                  name="date_of_birth"
                  type="date"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Date of birth is required.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="add_home_town">Home town *</label>
                <input
                  id="add_home_town"
                  name="home_town"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Home town is required.</div>
              </div>
              <div class="col-md-8">
                <label class="form-label" for="add_image">Profile image *</label>
                <input
                  id="add_image"
                  name="image"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  class="form-control"
                  data-max-size="1048576"
                  required
                />
                <div class="invalid-feedback">
                  Choose an image no larger than 1 MB.
                </div>
              </div>
              <div class="col-md-4">
                <label class="form-label" for="add_account_status">Account status *</label>
                <select
                  id="add_account_status"
                  name="account_status"
                  class="form-select"
                  required
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-primary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              class="btn btn-primary"
              type="submit"
            >
              Add user
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Profile -->
    <div
      class="modal fade"
      id="editUserModal"
      tabindex="-1"
      aria-labelledby="editUserTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form
          action="../actions/update_user_action.php"
          method="post"
          class="modal-content needs-validation"
          novalidate
          enctype="multipart/form-data"
        >
          <input id="edit_user_id" name="user_id" type="hidden" />
          <div class="modal-header">
            <h2 class="modal-title h4" id="editUserTitle">
              Edit user information
            </h2>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="edit_first_name">First name *</label>
                <input
                  id="edit_first_name"
                  name="first_name"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">First name is required.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_last_name">Last name *</label>
                <input
                  id="edit_last_name"
                  name="last_name"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Last name is required.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_email">Email *</label>
                <input
                  id="edit_email"
                  name="email"
                  type="email"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Enter a valid email.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_gender">Gender *</label>
                <select
                  id="edit_gender"
                  name="gender"
                  class="form-select"
                  required
                >
                  <option value="Female">Female</option>
                  <option value="Male">Male</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_date_of_birth">Date of birth *</label>
                <input
                  id="edit_date_of_birth"
                  name="date_of_birth"
                  type="date"
                  class="form-control"
                  required
                />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_home_town">Home town *</label>
                <input
                  id="edit_home_town"
                  name="home_town"
                  class="form-control"
                  required
                />
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_image">Change profile image</label>
                <input
                  id="edit_image"
                  name="image"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  data-max-size="1048576"
                  class="form-control"
                />
                <div class="invalid-feedback">Image must not exceed 1 MB.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="edit_account_status">Account status *</label>
                <select
                  id="edit_account_status"
                  name="account_status"
                  class="form-select"
                  required
                >
                  <option value="Active">Active</option>
                  <option value="InActive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-outline-primary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              class="btn btn-primary"
              type="submit"
            >
              Update user
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