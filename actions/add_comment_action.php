<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $user_id = $_POST["user_id"];
    $post_id = $_POST["post_id"];
    $comment = $_POST["comment"];
    $status = $_POST["status"];

    $user_id_valid = true;
    $post_id_valid = true;
    $position = 0;

    if ( !isset( $user_id[0] ) ) {
        $user_id_valid = false;
    }

    while ( isset( $user_id[$position] ) ) {
        if ( $user_id[$position] < "0" || $user_id[$position] > "9" ) {
            $user_id_valid = false;
        }
        $position++;
    }

    $position = 0;

    if ( !isset( $post_id[0] ) ) {
        $post_id_valid = false;
    }

    while ( isset( $post_id[$position] ) ) {
        if ( $post_id[$position] < "0" || $post_id[$position] > "9" ) {
            $post_id_valid = false;
        }
        $position++;
    }

    if ( $user_id_valid == false || $post_id_valid == false || $comment == "" ) {
        $_SESSION["comment_admin_error"] = "Select a user and post, then enter a comment.";
        header( "Location: ../admin/comments.php" );
        exit;
    }

    if ( $status != "Active" ) {
        $status = "InActive";
    }

    $clean_comment = "";
    $position = 0;

    while ( isset( $comment[$position] ) ) {
        if ( $comment[$position] == "'" ) {
            $clean_comment .= "\\'";
        } else {
            $clean_comment .= $comment[$position];
        }
        $position++;
    }

    $user_sql = "SELECT user_id FROM user WHERE user_id = $user_id AND is_active = 'Active'";
    $post_sql = "SELECT post_id FROM post WHERE post_id = $post_id AND post_status = 'Active'";
    $user_result = mysqli_query( $conn, $user_sql );
    $post_result = mysqli_query( $conn, $post_sql );
    $user_found = false;
    $post_found = false;

    if ( $user_result != false ) {
        if ( mysqli_fetch_assoc( $user_result ) != false ) {
            $user_found = true;
        }
    }

    if ( $post_result != false ) {
        if ( mysqli_fetch_assoc( $post_result ) != false ) {
            $post_found = true;
        }
    }

    if ( $user_found == false || $post_found == false ) {
        $_SESSION["comment_admin_error"] = "The selected active user or post was not found.";
        header( "Location: ../admin/comments.php" );
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
        '$status'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == true ) {
        $_SESSION["comment_admin_success"] = "Comment added successfully.";
    } else {
        $_SESSION["comment_admin_error"] = "Comment could not be added.";
    }
}

header( "Location: ../admin/comments.php" );
exit;

