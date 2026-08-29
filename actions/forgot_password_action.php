<?php

require "../config/database.php";
require "../includes/session.php";
require "../includes/send_email.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $email = $_POST["email"];

    if ( $email == "" ) {
        $_SESSION["forgot_error"] = "Email address is required.";
        header( "Location: ../forgot_password.php" );
        exit;
    }

    $has_at = false;
    $has_dot = false;
    $email_position = 0;

    while ( isset( $email[$email_position] ) ) {

        if ( $email[$email_position] == "@" ) {
            $has_at = true;
        }

        if ( $email[$email_position] == "." ) {
            $has_dot = true;
        }

        $email_position++;
    }

    if ( $has_at == false || $has_dot == false ) {
        $_SESSION["forgot_error"] = "Enter a valid email address.";
        header( "Location: ../forgot_password.php" );
        exit;
    }

    
    $sql = "SELECT user_id, first_name, password
    FROM user
    WHERE email = '$email'";

    $result = mysqli_query( $conn, $sql );

    if ( !$result || mysqli_num_rows( $result ) == 0 ) {
        $_SESSION["forgot_error"] = "No account was found with this email address.";
        header( "Location: ../forgot_password.php" );
        exit;
    }

    $user = mysqli_fetch_assoc( $result );
    $user_id = $user["user_id"];
    $old_password = $user["password"];
    $new_password = "Tal" . rand( 100000, 999999 );
    $stored_password = $new_password;

    
    $sql = "UPDATE user
    SET password = '$stored_password'
    WHERE user_id = $user_id";

    $result = mysqli_query( $conn, $sql );

    if ( !$result ) {
        $_SESSION["forgot_error"] = "Password could not be updated.";
        header( "Location: ../forgot_password.php" );
        exit;
    }

    $message = "Hello " . $user["first_name"] . ",\n\nYour temporary Tales password is: " . $new_password;
    $email_sent = send_email( $email, "Tales password recovery", $message );

    if ( $email_sent ) {
        $_SESSION["forgot_success"] = "A temporary password was emailed to your account.";
    } else {
        $restore_sql = "UPDATE user
        SET password = '$old_password'
        WHERE user_id = $user_id";

        mysqli_query( $conn, $restore_sql );

        $_SESSION["forgot_error"] = "Recovery email could not be sent. Your old password is still active.";
    }

    header( "Location: ../forgot_password.php" );
    exit;
}

header( "Location: ../forgot_password.php" );
exit;



