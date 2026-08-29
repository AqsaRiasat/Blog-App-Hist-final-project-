<?php

require "../config/database.php";
require "../includes/user_auth.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $user_id = $_SESSION["user_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $date_of_birth = $_POST["date_of_birth"];
    $address = $_POST["address"];

    if ( $first_name == "" || $last_name == "" || $email == "" || $gender == "" || $date_of_birth == "" || $address == "" ) {
        $_SESSION["profile_error"] = "All required profile fields must be completed.";
        header( "Location: ../profile.php" );
        exit;
    }

    $clean_fn = ""; $fn_len = 0;
    while ( isset( $first_name[$fn_len] ) ) { $fn_len++; }
    for ( $i = 0; $i < $fn_len; $i++ ) {
        if ( $first_name[$i] == "'" ) { $clean_fn .= "\\'"; } else { $clean_fn .= $first_name[$i]; }
    }

    $clean_ln = ""; $ln_len = 0;
    while ( isset( $last_name[$ln_len] ) ) { $ln_len++; }
    for ( $i = 0; $i < $ln_len; $i++ ) {
        if ( $last_name[$i] == "'" ) { $clean_ln .= "\\'"; } else { $clean_ln .= $last_name[$i]; }
    }

    $clean_email = ""; $em_len = 0;
    while ( isset( $email[$em_len] ) ) { $em_len++; }
    for ( $i = 0; $i < $em_len; $i++ ) {
        if ( $email[$i] == "'" ) { $clean_email .= "\\'"; } else { $clean_email .= $email[$i]; }
    }

    $clean_addr = ""; $ad_len = 0;
    while ( isset( $address[$ad_len] ) ) { $ad_len++; }
    for ( $i = 0; $i < $ad_len; $i++ ) {
        if ( $address[$i] == "'" ) { $clean_addr .= "\\'"; } else { $clean_addr .= $address[$i]; }
    }

    $chk_sql = "SELECT user_id FROM user WHERE email = '$clean_email' AND user_id != $user_id";
    $chk_res = mysqli_query( $conn, $chk_sql );
    if ( $chk_res && mysqli_num_rows( $chk_res ) > 0 ) {
        $_SESSION["profile_error"] = "Another user is using this email address.";
        header( "Location: ../profile.php" );
        exit;
    }

    $image_update_sql = "";
    if ( isset( $_FILES["image"] ) && $_FILES["image"]["error"] == 0 ) {
        if ( $_FILES["image"]["size"] > 1048576 ) {
            $_SESSION["profile_error"] = "Profile image must not be larger than 1 MB.";
            header( "Location: ../profile.php" );
            exit;
        }

        $raw_filename = $_FILES["image"]["name"];
        $file_length = 0;
        while ( isset( $raw_filename[$file_length] ) ) { $file_length++; }

        $ext = "";
        for ( $i = $file_length - 1; $i >= 0; $i-- ) {
            if ( $raw_filename[$i] == "." ) { break; }
            $ext = $raw_filename[$i] . $ext;
        }

        $ext_length = 0;
        while ( isset( $ext[$ext_length] ) ) { $ext_length++; }
        $clean_ext = "";
        for ( $i = 0; $i < $ext_length; $i++ ) {
            $char = $ext[$i];
            if ( $char >= 'A' && $char <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $char ) {
                            $clean_ext .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $clean_ext .= $char;
                }
        }

        if ( $clean_ext == "jpg" || $clean_ext == "jpeg" || $clean_ext == "png" || $clean_ext == "webp" ) {
            $img_name = time() . "." . $clean_ext;
            $img_path = "assets/images/users/" . $img_name;
            $upload_target = "../" . $img_path;

            if ( move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_target ) ) {
                $image_update_sql = ", user_image = '$img_path'";
            }
        }
    }

    $sql = "UPDATE user SET
        first_name = '$clean_fn',
        last_name = '$clean_ln',
        email = '$clean_email',
        gender = '$gender',
        date_of_birth = '$date_of_birth',
        address = '$clean_addr'
        $image_update_sql
    WHERE user_id = $user_id";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $_SESSION["first_name"] = $first_name;
        $_SESSION["last_name"] = $last_name;
        $_SESSION["email"] = $email;
        $_SESSION["profile_success"] = "Profile updated successfully.";
    } else {
        $_SESSION["profile_error"] = "Profile could not be updated.";
    }

    header( "Location: ../profile.php" );
    exit;
}

header( "Location: ../profile.php" );
exit;




