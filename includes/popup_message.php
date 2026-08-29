<?php

$popup_message = "";
$popup_type = "";

$success_keys = array(
    "profile_success", "profile_error",
    "follow_success", "follow_error",
    "comment_success", "comment_error",
    "register_error", "register_success",
    "login_error", "login_success",
    "forgot_success", "forgot_error",
    "feedback_success", "feedback_error",
    "user_success", "user_error",
    "post_success", "post_error",
    "category_success", "category_error",
    "comment_admin_success", "comment_admin_error",
    "settings_success", "settings_error"
);

$si = 0;
while ( isset( $success_keys[$si] ) ) {
    $key = $success_keys[$si];
    if ( isset( $_SESSION[$key] ) && $_SESSION[$key] != "" ) {
        $popup_message = $_SESSION[$key];

        $is_success = false;
        $kl = 0;
        while ( isset( $key[$kl] ) ) { $kl++; }

        $check = "success";
        $cl = 0;
        while ( isset( $check[$cl] ) ) { $cl++; }

        if ( $kl >= $cl ) {
            $match = true;
            $ci = 0;
            $ki = $kl - $cl;
            while ( $ci < $cl ) {
                if ( $key[$ki] != $check[$ci] ) { $match = false; }
                $ci++;
                $ki++;
            }
            if ( $match == true ) { $is_success = true; }
        }

        if ( $is_success ) {
            $popup_type = "success";
        } else {
            $popup_type = "error";
        }

        $_SESSION[$key] = null;
        break;
    }
    $si++;
}
?>

<?php if ( $popup_message != "" ) { ?>
<div id="jevelin-popup-overlay" class="jevelin-popup-overlay" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.72); backdrop-filter:blur(6px); z-index:999999; display:flex; align-items:center; justify-content:center;">
    <div id="jevelin-popup-box" class="jevelin-popup-box" style="background:#17151b !important; border:1px solid rgba(255,255,255,0.15) !important; border-radius:20px !important; padding:30px 26px 24px 26px !important; max-width:360px !important; width:90% !important; text-align:center !important; box-shadow:0 20px 60px rgba(0,0,0,0.7) !important;">
        <div class="jevelin-popup-icon <?php echo ($popup_type == 'success') ? 'jevelin-popup-icon-success' : 'jevelin-popup-icon-error'; ?>" style="width:50px; height:50px; border-radius:50%; margin:0 auto 16px auto; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:bold; <?php echo ($popup_type == 'success') ? 'background:rgba(243,186,64,0.2) !important; color:#f3ba40 !important; border:1.5px solid #f3ba40 !important;' : 'background:rgba(255,80,80,0.2) !important; color:#ff6b6b !important; border:1.5px solid #ff6b6b !important;'; ?>">
            <?php if ( $popup_type == "success" ) { ?>
                &#10003;
            <?php } else { ?>
                &#10007;
            <?php } ?>
        </div>

        <h3 class="jevelin-popup-title" style="color:#ffffff !important; font-family:'DM Sans', sans-serif !important; font-size:1.35rem !important; font-weight:800 !important; margin-bottom:10px !important; text-shadow:none !important;">
            <?php if ( $popup_type == "success" ) { ?>
                Success
            <?php } else { ?>
                Error
            <?php } ?>
        </h3>

        <p class="jevelin-popup-text" style="color:#f2f2f2 !important; font-family:'DM Sans', sans-serif !important; font-size:0.95rem !important; line-height:1.55 !important; margin-bottom:22px !important; font-weight:500 !important; opacity:1 !important; text-shadow:none !important;">
            <?php echo htmlspecialchars( $popup_message ); ?>
        </p>

        <button onclick="
            var overlay = document.getElementById('jevelin-popup-overlay');
            var box = document.getElementById('jevelin-popup-box');
            box.style.animation = 'popupFadeOut 0.2s ease forwards';
            overlay.style.transition = 'opacity 0.2s ease';
            overlay.style.opacity = '0';
            setTimeout(function() { overlay.remove(); }, 250);
        " class="jevelin-popup-btn <?php echo ($popup_type == 'success') ? 'jevelin-popup-btn-success' : 'jevelin-popup-btn-error'; ?>" style="border:none !important; padding:10px 42px !important; border-radius:999px !important; font-family:'DM Sans', sans-serif !important; font-size:0.95rem !important; font-weight:800 !important; cursor:pointer !important; <?php echo ($popup_type == 'success') ? 'background:#f3ba40 !important; color:#121212 !important;' : 'background:#ff6b6b !important; color:#ffffff !important;'; ?>">
            OK
        </button>
    </div>
</div>
<?php } ?>




