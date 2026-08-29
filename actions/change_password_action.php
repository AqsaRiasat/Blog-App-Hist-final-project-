<?php

require "../config/database.php";
require "../includes/user_auth.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $user_id = $_SESSION["user_id"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ( $current_password == "" || $new_password == "" ) {
        $_SESSION["profile_error"] = "Current and new password are required.";
        header( "Location: ../profile.php" );
        exit;
    }

    if ( $new_password != $confirm_password ) {
        $_SESSION["profile_error"] = "New passwords do not match.";
        header( "Location: ../profile.php" );
        exit;
    }

    $sql = "SELECT password FROM user WHERE user_id = $user_id";
    $result = mysqli_query( $conn, $sql );
    $user = mysqli_fetch_assoc( $result );

    if ( !$user ) {
        $_SESSION["profile_error"] = "User account not found.";
        header( "Location: ../profile.php" );
        exit;
    }

    $db_password = $user["password"];
    if ( $current_password != $db_password ) {
        $_SESSION["profile_error"] = "Current password is incorrect.";
        header( "Location: ../profile.php" );
        exit;
    }

    $pwd_len = 0;
    $has_letter = false;
    $has_number = false;
    while ( isset( $new_password[$pwd_len] ) ) {
        $ch = $new_password[$pwd_len];
        if ( ( $ch >= 'a' && $ch <= 'z' ) || ( $ch >= 'A' && $ch <= 'Z' ) ) { $has_letter = true; }
        if ( $ch >= '0' && $ch <= '9' ) { $has_number = true; }
        $pwd_len++;
    }

    if ( $pwd_len < 8 || $has_letter == false || $has_number == false ) {
        $_SESSION["profile_error"] = "New password must be at least 8 characters with letters and numbers.";
        header( "Location: ../profile.php" );
        exit;
    }

    $stored_password = $new_password;

    $update_sql = "UPDATE user
    SET password = '$stored_password'
    WHERE user_id = $user_id";
    $update_res = mysqli_query( $conn, $update_sql );

    if ( $update_res == true ) {
        $_SESSION["profile_success"] = "Password changed successfully.";
    } else {
        $_SESSION["profile_error"] = "Password could not be updated.";
    }

    header( "Location: ../profile.php" );
    exit;
}

header( "Location: ../profile.php" );
exit;




