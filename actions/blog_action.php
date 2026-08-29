<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $action = isset( $_POST["action"] ) ? $_POST["action"] : "";

    if ( $action == "add" ) {

        $blog_title = isset( $_POST["blog_title"] ) ? trim( $_POST["blog_title"] ) : "";
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
            header( "Location: ../admin/blogs.php" );
            exit;
        } else {
            $_SESSION["blog_error"] = "Failed to create blog: " . mysqli_error( $conn );
            header( "Location: ../admin/blog_form.php" );
            exit;
        }

    } else if ( $action == "edit" ) {

        $blog_id = isset( $_POST["blog_id"] ) ? (int)$_POST["blog_id"] : 0;
        $blog_title = isset( $_POST["blog_title"] ) ? trim( $_POST["blog_title"] ) : "";
        $post_per_page = isset( $_POST["post_per_page"] ) ? (int)$_POST["post_per_page"] : 3;
        $is_active = isset( $_POST["is_active"] ) && $_POST["is_active"] == "1" ? "Active" : "InActive";

        if ( $blog_id <= 0 || empty( $blog_title ) ) {
            $_SESSION["blog_error"] = "Valid blog title is required.";
            header( "Location: ../admin/blog_form.php?blog_id=" . $blog_id );
            exit;
        }

        if ( $post_per_page <= 0 ) {
            $post_per_page = 3;
        }

        $clean_title = mysqli_real_escape_string( $conn, $blog_title );
        $img_sql = "";

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
                $img_sql = ", blog_background_image = '$image_path'";
            }
        }

        $sql = "UPDATE blog SET
                    blog_title = '$clean_title',
                    post_per_page = $post_per_page,
                    blog_status = '$is_active'
                    $img_sql
                WHERE blog_id = $blog_id";

        if ( mysqli_query( $conn, $sql ) ) {
            $_SESSION["popup_message"] = "Blog updated successfully!";
            $_SESSION["popup_type"] = "success";
            header( "Location: ../admin/blogs.php" );
            exit;
        } else {
            $_SESSION["blog_error"] = "Failed to update blog: " . mysqli_error( $conn );
            header( "Location: ../admin/blog_form.php?blog_id=" . $blog_id );
            exit;
        }

    } else if ( $action == "toggle_status" ) {

        $blog_id = isset( $_POST["blog_id"] ) ? (int)$_POST["blog_id"] : 0;
        $current_status = isset( $_POST["current_status"] ) ? $_POST["current_status"] : "";

        if ( $blog_id > 0 ) {
            $new_status = ( $current_status == "Active" ) ? "InActive" : "Active";
            $sql = "UPDATE blog SET blog_status = '$new_status' WHERE blog_id = $blog_id";
            if ( mysqli_query( $conn, $sql ) ) {
                $_SESSION["popup_message"] = "Blog status updated to $new_status.";
                $_SESSION["popup_type"] = "success";
            } else {
                $_SESSION["popup_message"] = "Failed to update status.";
                $_SESSION["popup_type"] = "danger";
            }
        }

        header( "Location: ../admin/blogs.php" );
        exit;

    } else if ( $action == "delete" ) {

        $blog_id = isset( $_POST["blog_id"] ) ? (int)$_POST["blog_id"] : 0;

        if ( $blog_id > 0 ) {
            $sql = "DELETE FROM blog WHERE blog_id = $blog_id";
            if ( mysqli_query( $conn, $sql ) ) {
                $_SESSION["popup_message"] = "Blog deleted successfully.";
                $_SESSION["popup_type"] = "success";
            } else {
                $_SESSION["popup_message"] = "Failed to delete blog: " . mysqli_error( $conn );
                $_SESSION["popup_type"] = "danger";
            }
        }

        header( "Location: ../admin/blogs.php" );
        exit;
    }

}

header( "Location: ../admin/blogs.php" );
exit;
