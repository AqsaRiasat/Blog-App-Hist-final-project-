<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $blog_title = isset( $_POST["blog_title"] ) ? trim( $_POST["blog_title"] ) : "";
    $blog_description = isset( $_POST["blog_description"] ) ? trim( $_POST["blog_description"] ) : "";
    $post_per_page = isset( $_POST["post_per_page"] ) ? (int)$_POST["post_per_page"] : 3;
    $is_active = isset( $_POST["is_active"] ) && $_POST["is_active"] == "1" ? "Active" : "InActive";
    $user_id = isset( $_SESSION["user_id"] ) ? (int)$_SESSION["user_id"] : 1;

    if ( empty( $blog_title ) ) {
        $_SESSION["blog_error"] = "Blog title is required.";
        header( "Location: ../admin/blog_form.php" );
        exit;
    }

    if ( $post_per_page <= 0 ) {
        $post_per_page = 3;
    }

    $image_path = "assets/images/hero/child_reading_hero.png";
    if ( isset( $_FILES["blog_background_image"] ) && $_FILES["blog_background_image"]["error"] == 0 ) {
        $file_tmp = $_FILES["blog_background_image"]["tmp_name"];
        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename( $_FILES["blog_background_image"]["name"] ));
        $target_dir = "../assets/images/hero/";
        if ( !file_exists( $target_dir ) ) {
            mkdir( $target_dir, 0777, true );
        }
        $target_file = $target_dir . $file_name;
        if ( move_uploaded_file( $file_tmp, $target_file ) ) {
            $image_path = "assets/images/hero/" . $file_name;
        }
    }

    $clean_title = mysqli_real_escape_string( $conn, $blog_title );

    $sql = "INSERT INTO blog (user_id, blog_title, post_per_page, blog_background_image, blog_status, created_at)
            VALUES ($user_id, '$clean_title', $post_per_page, '$image_path', '$is_active', NOW())";

    if ( mysqli_query( $conn, $sql ) ) {
        $_SESSION["popup_message"] = "Blog created successfully!";
        $_SESSION["popup_type"] = "success";
        header( "Location: ../blogs.php" );
        exit;
    } else {
        $_SESSION["blog_error"] = "Failed to create blog: " . mysqli_error( $conn );
        header( "Location: ../admin/blog_form.php" );
        exit;
    }

} else {
    header( "Location: ../admin/blog_form.php" );
    exit;
}
