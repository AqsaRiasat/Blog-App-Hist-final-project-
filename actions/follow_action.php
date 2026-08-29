<?php

require "../config/database.php";
require "../includes/session.php";
require "../includes/send_email.php";

$redirect = "../index.php#follow-blog";

if ( isset( $_POST["post_id"] ) ) {
    $post_id = $_POST["post_id"];
    $redirect = "../post.php?post_id=$post_id";
}

if ( !isset( $_SESSION["user_id"] ) ) {
    header( "Location: ../login.php" );
    exit;
}

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $follower_id = $_SESSION["user_id"];
    $blog_id = $_POST["blog_id"];

    $sql = "SELECT user_id, email
    FROM user
    WHERE user_id = $follower_id
    AND is_approved = 'Approved'
    AND is_active = 'Active'";
    $result = mysqli_query( $conn, $sql );

    $user_found = false;
    $user = false;

    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $user_found = true;
            $user = $row;
        }
    }

    if ( $user_found == false ) {
        $_SESSION["follow_error"] = "Only approved and active users can follow a blog.";
        header( "Location: $redirect" );
        exit;
    }

    $sql = "SELECT blog_id, blog_title
    FROM blog
    WHERE blog_id = $blog_id
    AND blog_status = 'Active'";
    $result = mysqli_query( $conn, $sql );

    $blog_found = false;
    $blog = false;

    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $blog_found = true;
            $blog = $row;
        }
    }

    if ( $blog_found == false ) {
        $_SESSION["follow_error"] = "Blog is not available.";
        header( "Location: $redirect" );
        exit;
    }

    $sql = "SELECT follow_id, status
    FROM following_blog
    WHERE follower_id = $follower_id
    AND blog_following_id = $blog_id";
    $result = mysqli_query( $conn, $sql );

    $follow_found = false;
    $follow_id = 0;
    $current_status = "";

    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $follow_found = true;
            $follow_id = $row["follow_id"];
            $current_status = $row["status"];
        }
    }

    if ( $follow_found == true && $current_status == "Followed" ) {
        $sql = "UPDATE following_blog
        SET status = 'Unfollowed'
        WHERE follow_id = $follow_id";

        $res = mysqli_query( $conn, $sql );
        if ( $res == true ) {
            $_SESSION["follow_success"] = "You have unfollowed this blog.";
        } else {
            $_SESSION["follow_error"] = "Blog could not be unfollowed.";
        }
    } else {
        if ( $follow_found == true ) {
            $sql = "UPDATE following_blog
            SET status = 'Followed'
            WHERE follow_id = $follow_id";
        } else {
            $sql = "INSERT INTO following_blog (
                follower_id,
                blog_following_id,
                status
            ) VALUES (
                $follower_id,
                $blog_id,
                'Followed'
            )";
        }

        $res = mysqli_query( $conn, $sql );
        if ( $res == true ) {
            $subject = "You are following " . $blog["blog_title"];
            $message = "You will receive a short description whenever a new post is published.";
            send_email( $user["email"], $subject, $message );
            $_SESSION["follow_success"] = "You are now following this blog.";
        } else {
            $_SESSION["follow_error"] = "Blog could not be followed.";
        }
    }

    header( "Location: $redirect" );
    exit;
}

header( "Location: ../index.php" );
exit;

