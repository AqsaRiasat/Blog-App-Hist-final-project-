<?php

require "../config/database.php";
require "../includes/session.php";

if ( !isset( $_SESSION["user_id"] ) ) {
    header( "Location: ../login.php" );
    exit;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $user_id = $_SESSION["user_id"];
    $post_id = $_POST["post_id"];
    $comment = $_POST["comment_text"];

    
    if ( $comment == "" ) {
        $_SESSION["comment_error"] = "Comment is required.";
        header( "Location: ../post.php?post_id=$post_id" );
        exit;
    }

    
    $clean_comment = "";
    $length = 0;

    
    while ( isset( $comment[$length] ) ) {
        $length++;
    }

    
    for ( $i = 0; $i < $length; $i++ ) {
        if ( $comment[$i] == "'" ) {
            $clean_comment = $clean_comment . "\\'";
        } else {
            $clean_comment = $clean_comment . $comment[$i];
        }
    }

    
    $sql = "SELECT post_id
    FROM post
    WHERE post_id = $post_id
    AND post_status = 'Active'
    AND is_comment_allowed = 1";

    $result = mysqli_query( $conn, $sql );

    
    $post_found = false;

    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $post_found = true;
        }
    }

    if ( $post_found == false ) {
        $_SESSION["comment_error"] = "Comments are not allowed on this post.";
        header( "Location: ../post.php?post_id=$post_id" );
        exit;
    }

    
    $sql = "INSERT INTO post_comment (
        post_id,
        user_id,
        comment,
        is_active
    ) VALUES (
        $post_id,
        $user_id,
        '$clean_comment',
        'InActive'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $_SESSION["comment_success"] = "Comment submitted for administrator approval.";
    } else {
        $_SESSION["comment_error"] = "Comment could not be submitted.";
    }

    header( "Location: ../post.php?post_id=$post_id" );
    exit;
}

header( "Location: ../index.php" );
exit;


