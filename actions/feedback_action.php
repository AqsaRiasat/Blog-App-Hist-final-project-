<?php

require "../config/database.php";
require "../includes/session.php";
require "../includes/send_email.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $feedback = $_POST["message"];
    $user_id = "NULL";

    if ( isset( $_SESSION["user_id"] ) ) {
        $user_id = $_SESSION["user_id"];
    }

    
    if ( $name == "" || $email == "" || $feedback == "" ) {
        $_SESSION["feedback_error"] = "All feedback fields are required.";
        header( "Location: ../contact.php" );
        exit;
    }

    
    $has_at = false;
    $has_dot = false;
    $email_length = 0;

    while ( isset( $email[$email_length] ) ) {
        if ( $email[$email_length] == "@" ) {
            $has_at = true;
        }
        if ( $email[$email_length] == "." ) {
            $has_dot = true;
        }
        $email_length++;
    }

    if ( $has_at == false || $has_dot == false ) {
        $_SESSION["feedback_error"] = "Enter a valid email address.";
        header( "Location: ../contact.php" );
        exit;
    }

    
    
    $clean_name = "";
    $n_len = 0;
    while ( isset( $name[$n_len] ) ) { $n_len++; }
    for ( $i = 0; $i < $n_len; $i++ ) {
        if ( $name[$i] == "'" ) { $clean_name .= "\\'"; }
        else { $clean_name .= $name[$i]; }
    }

    
    $clean_email = "";
    $e_len = 0;
    while ( isset( $email[$e_len] ) ) { $e_len++; }
    for ( $i = 0; $i < $e_len; $i++ ) {
        if ( $email[$i] == "'" ) { $clean_email .= "\\'"; }
        else { $clean_email .= $email[$i]; }
    }

    
    $clean_feedback = "";
    $f_len = 0;
    while ( isset( $feedback[$f_len] ) ) { $f_len++; }
    for ( $i = 0; $i < $f_len; $i++ ) {
        if ( $feedback[$i] == "'" ) { $clean_feedback .= "\\'"; }
        else { $clean_feedback .= $feedback[$i]; }
    }

    
    $sql = "INSERT INTO user_feedback (
        user_id,
        user_name,
        user_email,
        feedback
    ) VALUES (
        $user_id,
        '$clean_name',
        '$clean_email',
        '$clean_feedback'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == false ) {
        $_SESSION["feedback_error"] = "Feedback could not be submitted.";
        header( "Location: ../contact.php" );
        exit;
    }

    
    $subject = "New feedback from " . $name;
    $message = "Sender email: " . $email . "\n\n" . $feedback;

    $email_sent = send_email( $smtp_email, $subject, $message );

    if ( $email_sent == true ) {
        $_SESSION["feedback_success"] = "Thank you. Your feedback was submitted successfully.";
    } else {
        $_SESSION["feedback_success"] = "Feedback was saved, but the email notification could not be sent.";
    }

    header( "Location: ../contact.php" );
    exit;
}

header( "Location: ../contact.php" );
exit;

