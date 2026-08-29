<?php

require "../config/database.php";
require "../includes/admin_auth.php";
require "../includes/send_email.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {

    $blog_id = $_POST["blog_id"];
    $category_id = $_POST["category_id"];
    $title = $_POST["title"];
    $summary = $_POST["short_description"];
    $description = $_POST["content"];
    $publish_date = $_POST["publish_date"];

    $allow_comments = 0;
    $post_status = "InActive";

    if ( isset( $_POST["allow_comments"] ) ) {
        $allow_comments = 1;
    }

    if ( isset( $_POST["is_active"] ) ) {
        $post_status = "Active";
    }

    
    if ( $blog_id == "" || $category_id == "" || $title == "" || $summary == "" || $description == "" ) {
        $_SESSION["post_error"] = "All required fields must be completed.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    if ( $publish_date == "" ) {
        $publish_date = date( "Y-m-d" );
    }

    
    $sql = "SELECT blog_id FROM blog WHERE blog_id = $blog_id AND blog_status = 'Active'";
    $result = mysqli_query( $conn, $sql );

    $blog_found = false;
    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $blog_found = true;
        }
    }

    if ( $blog_found == false ) {
        $_SESSION["post_error"] = "Select a valid active blog.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    
    $sql = "SELECT category_id FROM category WHERE category_id = $category_id AND category_status = 'Active'";
    $result = mysqli_query( $conn, $sql );

    $category_found = false;
    if ( $result != false ) {
        while ( $row = mysqli_fetch_assoc( $result ) ) {
            $category_found = true;
        }
    }

    if ( $category_found == false ) {
        $_SESSION["post_error"] = "Select a valid active category.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    
    if ( !isset( $_FILES["image"] ) || $_FILES["image"]["error"] != 0 ) {
        $_SESSION["post_error"] = "Featured image is required.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    
    $raw_filename = $_FILES["image"]["name"];
    $file_length = 0;
    while ( isset( $raw_filename[$file_length] ) ) {
        $file_length++;
    }

    $image_extension = "";
    for ( $i = $file_length - 1; $i >= 0; $i-- ) {
        if ( $raw_filename[$i] == "." ) {
            break;
        }
        $image_extension = $raw_filename[$i] . $image_extension;
    }

    
    $ext_length = 0;
    while ( isset( $image_extension[$ext_length] ) ) {
        $ext_length++;
    }

    $clean_extension = "";
    for ( $i = 0; $i < $ext_length; $i++ ) {
        $char = $image_extension[$i];
        if ( $char >= 'A' && $char <= 'Z' ) {
                    $upper_letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $lower_letters = "abcdefghijklmnopqrstuvwxyz";
                    $letter_position = 0;
                    while ( isset( $upper_letters[$letter_position] ) ) {
                        if ( $upper_letters[$letter_position] == $char ) {
                            $clean_extension .= $lower_letters[$letter_position];
                            break;
                        }
                        $letter_position++;
                    }
                } else {
                    $clean_extension .= $char;
                }
    }

    
    if ( $clean_extension != "jpg" && $clean_extension != "jpeg" && $clean_extension != "png" && $clean_extension != "webp" ) {
        $_SESSION["post_error"] = "Only JPG, PNG and WebP images are allowed.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    
    $image_name = time() . "." . $clean_extension;
    $image_path = "assets/images/posts/" . $image_name;
    $upload_path = "../" . $image_path;

    if ( !move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_path ) ) {
        $_SESSION["post_error"] = "Featured image could not be uploaded.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    // Reuse single image path for card and landscape columns
    $card_image_path = $image_path;
    $landscape_image_path = $image_path;

    $email_title = $title;
    $email_summary = $summary;

    
    
    $clean_title = "";
    $t_len = 0;
    while ( isset( $title[$t_len] ) ) { $t_len++; }
    for ( $i = 0; $i < $t_len; $i++ ) {
        if ( $title[$i] == "'" ) { $clean_title .= "\\'"; }
        else { $clean_title .= $title[$i]; }
    }

    
    $clean_summary = "";
    $s_len = 0;
    while ( isset( $summary[$s_len] ) ) { $s_len++; }
    for ( $i = 0; $i < $s_len; $i++ ) {
        if ( $summary[$i] == "'" ) { $clean_summary .= "\\'"; }
        else { $clean_summary .= $summary[$i]; }
    }

    
    $clean_description = "";
    $d_len = 0;
    while ( isset( $description[$d_len] ) ) { $d_len++; }
    for ( $i = 0; $i < $d_len; $i++ ) {
        if ( $description[$i] == "'" ) { $clean_description .= "\\'"; }
        else { $clean_description .= $description[$i]; }
    }

    
    $sql = "INSERT INTO post (
        blog_id,
        post_title,
        post_summary,
        post_description,
        featured_image,
        card_image,
        landscape_image,
        post_status,
        is_comment_allowed,
        created_at
    ) VALUES (
        $blog_id,
        '$clean_title',
        '$clean_summary',
        '$clean_description',
        '$image_path',
        '$card_image_path',
        '$landscape_image_path',
        '$post_status',
        $allow_comments,
        '$publish_date'
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == false ) {
        $_SESSION["post_error"] = "Post could not be created.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    $post_id = mysqli_insert_id( $conn );

    
    $sql = "INSERT INTO post_category (
        post_id,
        category_id
    ) VALUES (
        $post_id,
        $category_id
    )";

    $result = mysqli_query( $conn, $sql );

    if ( $result == false ) {
        $_SESSION["post_error"] = "Post category could not be saved.";
        header( "Location: ../admin/post_form.php" );
        exit;
    }

    
    if ( $post_status == "Active" ) {

        $sql = "SELECT user.email
        FROM following_blog
        INNER JOIN user
        ON following_blog.follower_id = user.user_id
        WHERE following_blog.blog_following_id = $blog_id
        AND following_blog.status = 'Followed'
        AND user.is_approved = 'Approved'
        AND user.is_active = 'Active'";

        $followers = mysqli_query( $conn, $sql );

        if ( $followers != false ) {
            while ( $follower = mysqli_fetch_assoc( $followers ) ) {
                $receiver_email = $follower["email"];
                $subject = "New post: " . $email_title;
                $message = $email_summary;

                send_email( $receiver_email, $subject, $message );
            }
        }
    }

    $_SESSION["post_success"] = "Post created successfully.";
    header( "Location: ../admin/posts.php" );
    exit;
}

header( "Location: ../admin/post_form.php" );
exit;



