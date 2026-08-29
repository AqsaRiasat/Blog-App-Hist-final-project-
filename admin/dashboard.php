<?php
  require "../includes/admin_auth.php";
?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Admin dashboard | Tales</title>
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
    <link href="../assets/css/styles.css?v=<?php echo time(); ?>" rel="stylesheet" />
  </head>
  <body data-admin-page="dashboard">
    <!-- Admin -->
    <div class="admin-shell">
      <div data-admin-sidebar></div>
      <!-- Content -->
      <main class="admin-main">
        <div data-admin-topbar></div>
        <!-- Admin -->

        <div class="d-flex justify-content-between align-items-end mb-4">
          <div>
            <span class="eyebrow">Overview</span>
            <h1>Dashboard</h1>
          </div>
          <a href="post_form.php" class="btn btn-primary"
            ><i class="bi bi-plus-lg"></i> New post</a
          >
        </div>
        <!-- Admin -->

        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl-3">
            <div class="surface stat-card">
              <div class="stat-icon"><i class="bi bi-people"></i></div>
              <p class="text-muted mb-0 mt-3">Total users</p>
              <h2 class="mb-0">1,248</h2>
              <small class="text-warning">+32 this month</small>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="surface stat-card">
              <div class="stat-icon"><i class="bi bi-file-text"></i></div>
              <p class="text-muted mb-0 mt-3">Active posts</p>
              <h2 class="mb-0">86</h2>
              <small class="text-muted">4 drafts</small>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="surface stat-card">
              <div class="stat-icon"><i class="bi bi-chat"></i></div>
              <p class="text-muted mb-0 mt-3">Comments</p>
              <h2 class="mb-0">3,412</h2>
              <small class="text-warning">12 pending</small>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="surface stat-card">
              <div class="stat-icon"><i class="bi bi-envelope"></i></div>
              <p class="text-muted mb-0 mt-3">New feedback</p>
              <h2 class="mb-0">18</h2>
              <small class="text-muted">This week</small>
            </div>
          </div>
        </div>
        <!-- Recent posts -->

        <div class="row g-4">
          <div class="col-xl-8">
            <div class="surface p-4">
              <div class="d-flex justify-content-between">
                <h2 class="h4">Recent posts</h2>
                <a href="posts.php">Manage all</a>
              </div>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead>
                    <tr>
                      <th>Post</th>
                      <th>Category</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <strong>The Door That Appeared at Midnight</strong>
                      </td>
                      <td>Animal Adventures</td>
                      <td><span class="status active">Active</span></td>
                      <td>Jul 18</td>
                    </tr>
                    <tr>
                      <td>
                        <strong>A humane approach to productivity</strong>
                      </td>
                      <td>Technology</td>
                      <td><span class="status active">Active</span></td>
                      <td>Jul 15</td>
                    </tr>
                    <tr>
                      <td><strong>The weekend field guide</strong></td>
                      <td>Fairy Tales</td>
                      <td><span class="status inactive">Inactive</span></td>
                      <td>Jul 12</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-4">
            <div class="surface p-4">
              <h2 class="h4">Pending actions</h2>
              <a href="users.php" class="recent-item"
                ><span class="stat-icon"
                  ><i class="bi bi-person-check"></i></span
                ><span
                  ><strong>8 account requests</strong
                  ><small class="d-block text-muted"
                    >Approve or reject</small
                  ></span
                ></a
              ><a href="comments.php" class="recent-item"
                ><span class="stat-icon"><i class="bi bi-chat"></i></span
                ><span
                  ><strong>12 comments</strong
                  ><small class="d-block text-muted"
                    >Awaiting review</small
                  ></span
                ></a
              ><a href="feedback.php" class="recent-item border-0"
                ><span class="stat-icon"><i class="bi bi-envelope"></i></span
                ><span
                  ><strong>5 unread messages</strong
                  ><small class="d-block text-muted"
                    >Review feedback</small
                  ></span
                ></a
              >
            </div>
          </div>
        </div>
      </main>
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Layout -->
    <script src="../assets/js/admin-components.js?v=<?php echo time(); ?>" defer></script>
    <!-- Shared application behavior -->
    <script src="../assets/js/app.js?v=<?php echo time(); ?>" defer></script>
  </body>
</html>
