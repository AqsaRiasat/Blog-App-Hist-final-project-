<?php

require "includes/user_auth.php";
require "config/database.php";

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM user WHERE user_id = $user_id LIMIT 1";
$result = mysqli_query( $conn, $sql );
$user = mysqli_fetch_assoc( $result );

if ( !$user ) {
    header( "Location: logout.php" );
    exit;
}

$full_name = $user["first_name"] . " " . $user["last_name"];

$user_image = "assets/images/admin/admin_profile.jpg";
if ( $user["user_image"] != "" ) {
    $user_image = $user["user_image"];
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>User dashboard | Tales</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="assets/css/styles.css?v=2.1" rel="stylesheet" />
  </head>
  <body data-page="profile">
    <?php require "includes/popup_message.php"; ?>

    <div class="admin-shell">
      <!-- User Sidebar Wrapper -->
      <div class="admin-sidebar-wrapper" data-admin-sidebar>
        <aside class="admin-sidebar" id="adminSidebar">
          <button class="admin-sidebar-close" type="button" data-admin-close aria-label="Close menu">
            <i class="bi bi-x-lg" aria-hidden="true"></i>
          </button>
          
          <div class="sidebar-user-profile text-center mb-4 pt-3 px-3">
            <img 
              src="<?php echo $user_image; ?>?v=kids2026" 
              class="rounded-circle border border-warning border-3 mb-2" 
              style="width: 72px; height: 72px; object-fit: cover; box-shadow: 0 4px 10px rgba(243, 186, 64, 0.3);" 
              alt="Avatar"
            />
            <h5 class="text-white mb-0" style="font-size: 0.95rem; font-weight: 700; word-break: break-all;"><?php echo htmlspecialchars($full_name); ?></h5>
            <span class="badge bg-warning text-dark mt-1" style="font-size: 0.65rem; font-weight: 700;">USER ACCOUNT</span>
          </div>

          <nav class="nav flex-column px-2">
            <a class="nav-link active" href="profile.php"><i class="bi bi-person me-2"></i>Edit Profile</a>
            <a class="nav-link" href="profile.php"><i class="bi bi-gear me-2"></i>Settings</a>
            <a class="nav-link" href="index.php"><i class="bi bi-box-arrow-left me-2"></i>View site</a>
            <a class="nav-link" href="logout.php"><i class="bi bi-power me-2"></i>Sign out</a>
          </nav>

          <!-- Sidebar Bottom Magical Daily Spin Widget -->
          <div class="mt-auto px-3 pt-3 pb-4 text-center">
            <h6 class="mb-2 text-warning fw-bold" style="font-size: 0.95rem; font-weight: 800; letter-spacing: 0.05em;">DAILY SPIN WHEEL</h6>
            
            <div class="d-inline-block position-relative mb-3" id="spin-wheel-container" style="transition: transform 3s cubic-bezier(0.25, 0.1, 0.25, 1); cursor: pointer; margin-top: 0.5rem;">
              <div style="font-size: 5.5rem; line-height: 1;">🎡</div>
            </div>
            
            <p class="text-white-50 mb-3" id="spin-result" style="font-size: 0.82rem; min-height: 24px; font-weight: 600;">Spin to unlock daily rewards!</p>
            <button type="button" class="btn btn-warning w-100 py-2" id="spin-wheel-btn" style="font-size: 0.85rem; font-weight: 800; color: #121212; border-radius: 10px; box-shadow: 0 4px 12px rgba(243, 186, 64, 0.2);">Spin Wheel 🎡</button>
          </div>
        </aside>
      </div>

      <!-- Backdrop for mobile -->
      <button type="button" class="admin-sidebar-backdrop" id="sidebarBackdrop" aria-label="Close menu"></button>

      <!-- Content -->
      <main class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar d-flex justify-content-between align-items-center">
          <!-- Left side: User profile image -->
          <div class="d-flex align-items-center gap-2">
            <img src="<?php echo $user_image; ?>" alt="User" class="rounded-circle border border-warning" style="width: 32px; height: 32px; object-fit: cover; box-shadow: 0 2px 6px rgba(243, 186, 64, 0.25);">
            <span class="text-white fw-bold d-none d-sm-inline" style="font-size: 0.8rem;"><?php echo htmlspecialchars($user["first_name"]); ?></span>
          </div>
          <!-- Right side: Horizontal nav links on mobile -->
          <nav class="mobile-nav-links d-flex align-items-center gap-2">
            <a href="profile.php" class="mobile-top-link active"><i class="bi bi-person"></i><span class="d-none d-md-inline ms-1">Edit Profile</span><span class="d-inline d-md-none ms-1">Edit</span></a>
            <a href="profile.php" class="mobile-top-link"><i class="bi bi-gear"></i><span class="d-none d-md-inline ms-1">Settings</span></a>
            <a href="index.php" class="mobile-top-link"><i class="bi bi-box-arrow-left"></i><span class="d-none d-md-inline ms-1">View site</span><span class="d-inline d-md-none ms-1">View</span></a>
            <a href="logout.php" class="mobile-top-link text-danger"><i class="bi bi-power"></i><span class="d-none d-md-inline ms-1">Sign out</span><span class="d-inline d-md-none ms-1">Out</span></a>
          </nav>
        </header>

        <div class="profile-workspace w-100">
          <!-- Welcome Banner with Illustration -->
          <div class="welcome-heading mb-2 p-3 py-3 rounded-4" style="background: #242424; border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);">
            <div class="row align-items-center">
              <div class="col-md-8">
                <span class="eyebrow text-uppercase font-weight-bold" style="letter-spacing: 0.15em; font-size: 0.72rem; color: #f3ba40 !important;">Welcome back</span>
                <h1 class="font-weight-black my-1" style="font-weight: 800; color: #ffffff; font-size: 1.85rem;">Glad to see you, <?php echo htmlspecialchars($user["first_name"]); ?>! 🌟</h1>
                <p class="mb-1" style="font-size: 0.95rem; color: rgba(255, 255, 255, 0.75);">Here's your personal space. Manage your settings, update credentials, or play quests below!</p>
                <div style="font-size: 0.85rem; color: #f3ba40 !important; font-weight: 700; display: inline-flex; align-items: center; gap: 0.4rem;">
                  <i class="bi bi-calendar3"></i> <?php echo date("l, F j, Y"); ?>
                </div>
              </div>
              <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                <img 
                  src="assets/images/kids_dashboard_illustration.jpg?v=<?php echo time(); ?>" 
                  alt="Stories and Magic" 
                  class="img-fluid rounded-4" 
                  style="max-height: 125px; object-fit: cover;"
                />
              </div>
            </div>
          </div>

          <!-- Bottom Section: Playful Kids static UI dashboard widgets -->
          <div class="row g-2 mb-2">
            <!-- Kids Widget 1: My Adventure Books -->
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="900" data-aos-delay="100">
              <div class="kids-card">
                <div class="d-flex align-items-center mb-3">
                  <div class="badge-circle me-3">🧚‍♀️</div>
                  <div>
                    <span class="badge bg-warning text-dark px-2 mb-1" style="font-size: 0.65rem; font-weight: 700;">PLAY ROOM</span>
                    <h3 class="h5 mb-0 text-white font-weight-bold">Adventure Island</h3>
                  </div>
                </div>
                <p class="text-white-50 small mb-3">Track your reading milestones and unlock magical maps as you read more stories!</p>
                <div class="mb-2 d-flex justify-content-between text-white font-weight-bold" style="font-size: 0.8rem;">
                  <span>Stories Explored</span>
                  <span>3 / 5 completed</span>
                </div>
                <div class="progress" style="height: 10px; background: #1a1a1a; border-radius: 99px;">
                  <div class="progress-bar" role="progressbar" style="width: 60%; background: #f3ba40; border-radius: 99px;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-warning d-block mt-2 font-weight-bold" style="font-size: 0.75rem;"><i class="bi bi-gift-fill me-1"></i> 2 more stories to get next reward!</small>
              </div>
            </div>

            <!-- Kids Widget 2: Daily Quests -->
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="900" data-aos-delay="200">
              <div class="kids-card">
                <div class="d-flex align-items-center mb-3">
                  <div class="badge-circle me-3">🎯</div>
                  <div>
                    <span class="badge bg-danger text-white px-2 mb-1" style="font-size: 0.65rem; font-weight: 700;">DAILY MISSION</span>
                    <h3 class="h5 mb-0 text-white font-weight-bold">Tale Quests</h3>
                  </div>
                </div>
                <div class="quest-list">
                  <div class="quest-list-item text-white">
                    <i class="bi bi-check-circle-fill text-warning me-2"></i>
                    <span>Read a mystery story today</span>
                  </div>
                  <div class="quest-list-item text-white">
                    <i class="bi bi-circle text-white-50 me-2"></i>
                    <span>Write 1 kind comment</span>
                  </div>
                </div>
                <small class="text-white-50 d-block mt-3" style="font-size: 0.75rem;"><i class="bi bi-stars text-warning me-1"></i> Completing quests earns golden keys!</small>
              </div>
            </div>

            <!-- Kids Widget 3: My Badges -->
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="900" data-aos-delay="300">
              <div class="kids-card">
                <div class="d-flex align-items-center mb-3">
                  <div class="badge-circle me-3">🏆</div>
                  <div>
                    <span class="badge bg-success text-white px-2 mb-1" style="font-size: 0.65rem; font-weight: 700;">MY TROPHIES</span>
                    <h3 class="h5 mb-0 text-white font-weight-bold">Unlocked Badges</h3>
                  </div>
                </div>
                <p class="text-white-50 small mb-3">Check out the badges you've earned from your magical adventures!</p>
                <div class="text-center">
                  <div class="kids-badge-item">
                    <div class="badge-circle">🐛</div>
                    <span class="badge-title">Bookworm</span>
                  </div>
                  <div class="kids-badge-item">
                    <div class="badge-circle">✍️</div>
                    <span class="badge-title">Storyteller</span>
                  </div>
                  <div class="kids-badge-item">
                    <div class="badge-circle">⭐️</div>
                    <span class="badge-title">Star Kid</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Profile Info Cards (Side by Side on Desktop) -->
          <div class="row g-2 mt-0">
            <!-- Column 1: Personal Details Form -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="900">
              <form
                action="actions/update_profile_action.php"
                method="post"
                class="profile-details-form profile-glass-card needs-validation" 
                novalidate
                enctype="multipart/form-data">
                
                <section class="profile-information-panel">
                  <div class="profile-card-heading mb-2">
                    <span class="eyebrow">Profile details</span>
                    <h2 class="h3 font-weight-bold text-white mb-0">Personal Information</h2>
                  </div>

                  <div class="row g-2">
                    <div class="col-md-6">
                      <label class="form-label" for="profile_first_name">First name *</label>
                      <input
                        class="form-control"
                        id="profile_first_name"
                        name="first_name"
                        value="<?php echo htmlspecialchars($user["first_name"]); ?>" 
                        required />
                    </div>
                    
                    <div class="col-md-6">
                      <label class="form-label" for="profile_last_name">Last name *</label>
                      <input
                        class="form-control"
                        id="profile_last_name"
                        name="last_name"
                        value="<?php echo htmlspecialchars($user["last_name"]); ?>" 
                        required />
                    </div>

                    <div class="col-12">
                      <label class="form-label" for="profile_email">Email *</label>
                      <input
                        class="form-control"
                        id="profile_email"
                        name="email"
                        type="email"
                        value="<?php echo htmlspecialchars($user["email"]); ?>" 
                        required />
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="profile_gender">Gender *</label>
                      <select class="form-select" id="profile_gender" name="gender" required>
                        <option value="Female" <?php if ( $user["gender"] == "Female" ) { echo "selected"; } ?>>Female</option>
                        <option value="Male" <?php if ( $user["gender"] == "Male" ) { echo "selected"; } ?>>Male</option>
                      </select>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="profile_dob">Date of birth *</label>
                      <input
                        class="form-control"
                        id="profile_dob"
                        name="date_of_birth"
                        type="date"
                        value="<?php echo htmlspecialchars($user["date_of_birth"]); ?>" 
                        required />
                    </div>

                    <div class="col-12">
                      <label class="form-label" for="profile_hometown">Address / Home Town *</label>
                      <input
                        class="form-control"
                        id="profile_hometown"
                        name="address"
                        value="<?php echo htmlspecialchars($user["address"]); ?>" 
                        required />
                    </div>

                    <div class="col-12">
                      <label class="form-label" for="profile_image">Change profile picture</label>
                      <input
                        class="form-control"
                        id="profile_image"
                        name="image"
                        type="file"
                        accept="image/*"
                        data-max-size="1048576" />
                      <div class="invalid-feedback">Image must not exceed 1 MB.</div>
                      <div class="form-text text-white-50">JPG, PNG, or WebP. Maximum 1 MB.</div>
                    </div>
                  </div>

                  <div class="profile-card-actions mt-2">
                    <button class="btn btn-primary px-4 py-2 w-100" type="submit" style="background: #f3ba40; color: #121212; border: none; font-weight: 700; border-radius: 8px;">Update profile</button>
                  </div>
                </section>
              </form>
            </div>

            <!-- Column 2: Password Security & Mascot Quote Card -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="900">
              <section class="profile-glass-card profile-security-card mb-2">
                <div class="profile-card-heading mb-3">
                  <span class="eyebrow">Account security</span>
                  <h2 class="h3 font-weight-bold text-white mb-0">Change Password</h2>
                  <p class="small text-white-50 mb-0 mt-1" style="white-space: nowrap !important; font-size: 0.72rem;">Use at least eight characters with a letter and a number.</p>
                </div>

                <form action="actions/change_password_action.php" method="post" class="row g-2 needs-validation" novalidate>
                  <div class="col-12">
                    <label class="form-label" for="current_password">Current password</label>
                    <input class="form-control" id="current_password" name="current_password" type="password" required />
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="new_password">New password</label>
                    <input
                      class="form-control"
                      id="new_password"
                      name="new_password"
                      type="password"
                      minlength="8"
                      pattern="(?=.*[A-Za-z])(?=.*\d).{8,}" 
                      required />
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="confirm_new_password">Confirm new password</label>
                    <input
                      class="form-control"
                      id="confirm_new_password"
                      name="confirm_password"
                      type="password" 
                      required />
                    <div class="invalid-feedback">Passwords must match.</div>
                  </div>

                  <div class="profile-card-actions mt-4">
                    <button class="btn btn-outline-primary px-4 py-2 w-100" type="submit" style="color: #f3ba40; border-color: #f3ba40; font-weight: 700; border-radius: 8px;">Change password</button>
                  </div>
                </form>
              </section>

              <!-- Mascot Bedtime Quote Card (Fixed height to align bottom edges perfectly with Personal Information card) -->
              <div class="profile-glass-card d-flex align-items-center gap-3" style="background: #242424 !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; border-radius: 20px !important; min-height: 145px; max-height: 145px; padding: 0.65rem 1.2rem !important;">
                <div class="owl-dynamic" style="font-size: 3.5rem; line-height: 1;">🦉</div>
                <div>
                  <h6 class="text-warning fw-bold mb-1" style="font-size: 0.9rem; letter-spacing: 0.05em; font-weight: 800;">OWL WISDOM</h6>
                  <p class="mb-0 text-white-50" style="font-size: 0.82rem; line-height: 1.35; font-weight: 600;">"Reading just 15 minutes before bed takes you on magical dreams! Keep exploring new tales, <?php echo htmlspecialchars($user["first_name"]); ?>!" 📚✨</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/components.js?v=20260730-tales-brand"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="assets/js/app.js?v=20260730-children-content"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.querySelector("[data-admin-toggle]");
        const sidebar = document.getElementById("adminSidebar");
        const close = document.querySelector("[data-admin-close]");
        const backdrop = document.getElementById("sidebarBackdrop");
        
        function openSidebar() {
          sidebar.classList.add("open");
          backdrop.classList.add("show");
        }
        
        function closeSidebar() {
          sidebar.classList.remove("open");
          backdrop.classList.remove("show");
        }
        
        if (toggle) {
          toggle.addEventListener("click", openSidebar);
        }
        if (close) {
          close.addEventListener("click", closeSidebar);
        }
        if (backdrop) {
          backdrop.addEventListener("click", closeSidebar);
        }

        // Spin Wheel Interactive Logic
        const spinBtn = document.getElementById("spin-wheel-btn");
        const wheelContainer = document.getElementById("spin-wheel-container");
        const spinResult = document.getElementById("spin-result");
        let hasSpun = false;
        
        const prizes = [
          "🔑 Golden Key unlocked!",
          "⭐️ +20 Magic XP earned!",
          "🏆 Tale Collector Badge!",
          "📖 Bedtime Story unlocked!"
        ];
        
        if (spinBtn) {
          spinBtn.addEventListener("click", () => {
            if (hasSpun) {
              spinResult.innerText = "You already spun today! Come back tomorrow 🌟";
              return;
            }
            hasSpun = true;
            spinBtn.disabled = true;
            spinBtn.classList.add("btn-secondary");
            
            const randomRotation = 1440 + Math.floor(Math.random() * 360);
            wheelContainer.style.transform = `rotate(${randomRotation}deg)`;
            
            spinResult.innerText = "Spinning...";
            
            setTimeout(() => {
              const prize = prizes[Math.floor(Math.random() * prizes.length)];
              spinResult.innerText = `🎉 ${prize}`;
            }, 3000);
          });
        }
      });
    </script>
  </body>
</html>
