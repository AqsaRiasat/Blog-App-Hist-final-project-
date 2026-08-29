<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	$post_id = $_POST["post_id"];
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

	if ( $post_id == "" || $category_id == "" || $title == "" || $summary == "" || $description == "" || $publish_date == "" ) {
		$_SESSION["post_error"] = "All required post fields must be completed.";
		header( "Location: ../admin/edit_post.php?post_id=$post_id" );
		exit;
	}
        $clean_title = "";
        $title_position = 0;

        while ( isset( $title[$title_position] ) ) {
            $title_character = $title[$title_position];

            if ( $title_character == "\\" ) {
                $clean_title .= "\\\\";
            } else if ( $title_character == "'" ) {
                $clean_title .= "\\'";
            } else {
                $clean_title .= $title_character;
            }

            $title_position++;
        }

        $title = $clean_title;
        $clean_summary = "";
        $summary_position = 0;

        while ( isset( $summary[$summary_position] ) ) {
            $summary_character = $summary[$summary_position];

            if ( $summary_character == "\\" ) {
                $clean_summary .= "\\\\";
            } else if ( $summary_character == "'" ) {
                $clean_summary .= "\\'";
            } else {
                $clean_summary .= $summary_character;
            }

            $summary_position++;
        }

        $summary = $clean_summary;
        $clean_description = "";
        $description_position = 0;

        while ( isset( $description[$description_position] ) ) {
            $description_character = $description[$description_position];

            if ( $description_character == "\\" ) {
                $clean_description .= "\\\\";
            } else if ( $description_character == "'" ) {
                $clean_description .= "\\'";
            } else {
                $clean_description .= $description_character;
            }

            $description_position++;
        }

        $description = $clean_description;
	$image_sql = "";

	if ( isset( $_FILES["image"] ) && $_FILES["image"]["error"] == 0 ) {
		$image_name = $_FILES["image"]["name"];
		$image_length = 0;
		$image_extension = "";

		while ( isset( $image_name[$image_length] ) ) {
			$image_length++;
		}

		for ( $i = $image_length - 1; $i >= 0; $i-- ) {
			if ( $image_name[$i] == "." ) {
				break;
			}
			$image_extension = $image_name[$i] . $image_extension;
		}

		if ( $image_extension != "jpg" && $image_extension != "jpeg" && $image_extension != "png" && $image_extension != "webp" ) {
			$_SESSION["post_error"] = "Only JPG, PNG and WebP images are allowed.";
			header( "Location: ../admin/edit_post.php?post_id=$post_id" );
			exit;
		}

		$new_image_name = time() . "." . $image_extension;
		$image_path = "assets/images/posts/" . $new_image_name;
		$upload_path = "../" . $image_path;

		if ( move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_path ) ) {
			$image_sql .= ", featured_image = '$image_path', card_image = '$image_path', landscape_image = '$image_path'";
		}
	}

	$sql = "UPDATE post SET
	post_title = '$title',
	post_summary = '$summary',
	post_description = '$description',
	post_status = '$post_status',
	is_comment_allowed = $allow_comments,
	created_at = '$publish_date'
	$image_sql
	WHERE post_id = $post_id";

	$result = mysqli_query( $conn, $sql );

	if ( $result == true ) {
		$category_sql = "UPDATE post_category SET category_id = $category_id WHERE post_id = $post_id";
		mysqli_query( $conn, $category_sql );
		$_SESSION["post_success"] = "Post updated successfully.";
		header( "Location: ../admin/posts.php" );
		exit;
	}

	$_SESSION["post_error"] = "Post could not be updated.";
	header( "Location: ../admin/edit_post.php?post_id=$post_id" );
	exit;
}

header( "Location: ../admin/posts.php" );
exit;


