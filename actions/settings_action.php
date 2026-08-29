<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
    $user_id = $_SESSION["user_id"];
    $blog_id = $_POST["blog_id"];
    $blog_title = $_POST["blog_title"];
    $posts_per_page = $_POST["posts_per_page"];
    $blog_description = $_POST["blog_description"];
    $post_title_color = $_POST["post_title_color"];
    $post_background_color = $_POST["post_background_color"];
    $font_style = $_POST["font_style"];

    $blog_id_is_valid = true;
    $blog_id_position = 0;
    if ( !isset( $blog_id[0] ) ) {
        $blog_id_is_valid = false;
    }
    while ( isset( $blog_id[$blog_id_position] ) ) {
        if ( $blog_id[$blog_id_position] < "0" || $blog_id[$blog_id_position] > "9" ) {
            $blog_id_is_valid = false;
        }
        $blog_id_position++;
    }

    $posts_per_page_is_valid = true;
    $posts_position = 0;
    if ( !isset( $posts_per_page[0] ) ) {
        $posts_per_page_is_valid = false;
    }
    while ( isset( $posts_per_page[$posts_position] ) ) {
        if ( $posts_per_page[$posts_position] < "0" || $posts_per_page[$posts_position] > "9" ) {
            $posts_per_page_is_valid = false;
        }
        $posts_position++;
    }

    if (
        $blog_id_is_valid == false ||
        $posts_per_page_is_valid == false ||
        $blog_title == "" ||
        $posts_per_page < 1 ||
        $posts_per_page > 50
    ) {
        $_SESSION["settings_error"] = "Enter a blog title and posts per page between 1 and 50.";
        header( "Location: ../admin/settings.php" );
        exit;
    }
        $clean_blog_title = "";
        $blog_title_position = 0;

        while ( isset( $blog_title[$blog_title_position] ) ) {
            $blog_title_character = $blog_title[$blog_title_position];

            if ( $blog_title_character == "\\" ) {
                $clean_blog_title .= "\\\\";
            } else if ( $blog_title_character == "'" ) {
                $clean_blog_title .= "\\'";
            } else {
                $clean_blog_title .= $blog_title_character;
            }

            $blog_title_position++;
        }

        $blog_title = $clean_blog_title;
        $clean_blog_description = "";
        $blog_description_position = 0;

        while ( isset( $blog_description[$blog_description_position] ) ) {
            $blog_description_character = $blog_description[$blog_description_position];

            if ( $blog_description_character == "\\" ) {
                $clean_blog_description .= "\\\\";
            } else if ( $blog_description_character == "'" ) {
                $clean_blog_description .= "\\'";
            } else {
                $clean_blog_description .= $blog_description_character;
            }

            $blog_description_position++;
        }

        $blog_description = $clean_blog_description;
        $clean_post_title_color = "";
        $post_title_color_position = 0;

        while ( isset( $post_title_color[$post_title_color_position] ) ) {
            $post_title_color_character = $post_title_color[$post_title_color_position];

            if ( $post_title_color_character == "\\" ) {
                $clean_post_title_color .= "\\\\";
            } else if ( $post_title_color_character == "'" ) {
                $clean_post_title_color .= "\\'";
            } else {
                $clean_post_title_color .= $post_title_color_character;
            }

            $post_title_color_position++;
        }

        $post_title_color = $clean_post_title_color;
        $clean_post_background_color = "";
        $post_background_color_position = 0;

        while ( isset( $post_background_color[$post_background_color_position] ) ) {
            $post_background_color_character = $post_background_color[$post_background_color_position];

            if ( $post_background_color_character == "\\" ) {
                $clean_post_background_color .= "\\\\";
            } else if ( $post_background_color_character == "'" ) {
                $clean_post_background_color .= "\\'";
            } else {
                $clean_post_background_color .= $post_background_color_character;
            }

            $post_background_color_position++;
        }

        $post_background_color = $clean_post_background_color;
        $clean_font_style = "";
        $font_style_position = 0;

        while ( isset( $font_style[$font_style_position] ) ) {
            $font_style_character = $font_style[$font_style_position];

            if ( $font_style_character == "\\" ) {
                $clean_font_style .= "\\\\";
            } else if ( $font_style_character == "'" ) {
                $clean_font_style .= "\\'";
            } else {
                $clean_font_style .= $font_style_character;
            }

            $font_style_position++;
        }

        $font_style = $clean_font_style;
    $background_sql = "";

    if ( isset( $_FILES["background_image"] ) && $_FILES["background_image"]["name"] != "" ) {
        $file_name = $_FILES["background_image"]["name"];
        $extension = "";
        $position = 0;

        while ( isset( $file_name[$position] ) ) {
            if ( $file_name[$position] == "." ) {
                $extension = "";
            } else {
                $extension .= $file_name[$position];
            }
            $position++;
        }

        if ( $extension == "jpg" || $extension == "jpeg" || $extension == "png" || $extension == "webp" ) {
            $new_name = "blog_background_" . date( "YmdHis" ) . "." . $extension;
            $save_path = "../assets/images/hero/" . $new_name;

            if ( move_uploaded_file( $_FILES["background_image"]["tmp_name"], $save_path ) ) {
                $database_path = "assets/images/hero/" . $new_name;
                $background_sql = ", blog_background_image = '$database_path'";
            }
        }
    }

    $blog_sql = "UPDATE blog SET
        blog_title = '$blog_title',
        post_per_page = '$posts_per_page'
        $background_sql,
        updated_at = NOW()
    WHERE blog_id = $blog_id";

    $blog_updated = mysqli_query( $conn, $blog_sql );

    $delete_sql = "DELETE FROM setting
    WHERE user_id = $user_id
    AND setting_key IN (
        'blog_description',
        'post_title_color',
        'post_background_color',
        'font_style'
    )";
    mysqli_query( $conn, $delete_sql );

    $description_sql = "INSERT INTO setting (
        user_id, setting_key, setting_value, setting_status
    ) VALUES (
        $user_id, 'blog_description', '$blog_description', 'Active'
    )";
    $title_color_sql = "INSERT INTO setting (
        user_id, setting_key, setting_value, setting_status
    ) VALUES (
        $user_id, 'post_title_color', '$post_title_color', 'Active'
    )";
    $background_color_sql = "INSERT INTO setting (
        user_id, setting_key, setting_value, setting_status
    ) VALUES (
        $user_id, 'post_background_color', '$post_background_color', 'Active'
    )";
    $font_sql = "INSERT INTO setting (
        user_id, setting_key, setting_value, setting_status
    ) VALUES (
        $user_id, 'font_style', '$font_style', 'Active'
    )";

    $description_saved = mysqli_query( $conn, $description_sql );
    $title_color_saved = mysqli_query( $conn, $title_color_sql );
    $background_color_saved = mysqli_query( $conn, $background_color_sql );
    $font_saved = mysqli_query( $conn, $font_sql );

    if (
        $blog_updated &&
        $description_saved &&
        $title_color_saved &&
        $background_color_saved &&
        $font_saved
    ) {
        $_SESSION["settings_success"] = "Blog settings saved successfully.";
    } else {
        $_SESSION["settings_error"] = "Blog settings could not be saved.";
    }
}

header( "Location: ../admin/settings.php" );
exit;




