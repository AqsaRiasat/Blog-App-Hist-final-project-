<?php

require "../includes/admin_auth.php";
require "../config/database.php";
require "../includes/send_email.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $user_id = $_POST["user_id"];
    $action = $_POST["action"];

    
    $sql = "SELECT first_name, email
    FROM user
    WHERE user_id = $user_id
    AND role_id = 2";

    $user_result = mysqli_query( $conn, $sql );
    
    $user_found = false;
    $user = false;

    if ( $user_result != false ) {
        while ( $row = mysqli_fetch_assoc( $user_result ) ) {
            $user_found = true;
            $user = $row;
        }
    }

    if ( $user_found == false ) {
        $_SESSION["user_error"] = "User account was not found.";
        header( "Location: ../admin/users.php" );
        exit;
    }

    
    if ( $action == "approve" ) {

        $sql = "UPDATE user
        SET
            is_approved = 'Approved',
            is_active = 'Active'
        WHERE user_id = $user_id
        AND role_id = 2";

        $message = "User account approved successfully.";
        $email_subject = "Tales account approved";
        $email_message = "Hello " . $user["first_name"] . ", your Tales account was approved and activated.";

    } else if ( $action == "reject" ) {

        $sql = "UPDATE user
        SET
            is_approved = 'Rejected',
            is_active = 'InActive'
        WHERE user_id = $user_id
        AND role_id = 2";

        $message = "User account rejected successfully.";
        $email_subject = "Tales account rejected";
        $email_message = "Hello " . $user["first_name"] . ", your Tales account request was rejected.";

    } else if ( $action == "activate" ) {

        $sql = "UPDATE user
        SET is_active = 'Active'
        WHERE user_id = $user_id
        AND role_id = 2
        AND is_approved = 'Approved'";

        $message = "User account activated successfully.";
        $email_subject = "Tales account activated";
        $email_message = "Hello " . $user["first_name"] . ", your Tales account was activated.";

    } else if ( $action == "deactivate" ) {

        $sql = "UPDATE user
        SET is_active = 'InActive'
        WHERE user_id = $user_id
        AND role_id = 2";

        $message = "User account deactivated successfully.";
        $email_subject = "Tales account deactivated";
        $email_message = "Hello " . $user["first_name"] . ", your Tales account was deactivated by the administrator.";

    } else {

        $_SESSION["user_error"] = "Invalid user action.";
        header( "Location: ../admin/users.php" );
        exit;

    }

    $result = mysqli_query( $conn, $sql );

    
    if ( $result == true ) {
        send_email( $user["email"], $email_subject, $email_message );
        $_SESSION["user_success"] = $message;
    } else {
        $_SESSION["user_error"] = "User account could not be updated.";
    }

    header( "Location: ../admin/users.php" );
    exit;

} else {

    header( "Location: ../admin/users.php" );
    exit;
}

