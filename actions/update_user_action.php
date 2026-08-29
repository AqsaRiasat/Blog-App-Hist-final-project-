<?php

require "../includes/admin_auth.php";
require "../config/database.php";

if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
	$user_id = $_POST["user_id"];
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$email = $_POST["email"];
	$gender = $_POST["gender"];
	$date_of_birth = $_POST["date_of_birth"];
	$address = $_POST["home_town"];
	$account_status = $_POST["account_status"];

	if ( $user_id == "" || $first_name == "" || $last_name == "" || $email == "" || $gender == "" || $date_of_birth == "" || $address == "" ) {
		$_SESSION["user_error"] = "All required user fields must be completed.";
		header( "Location: ../admin/users.php" );
		exit;
	}

	if ( $account_status == "Active" ) {
		$db_status = "Active";
	} else {
		$db_status = "InActive";
	}
        $clean_first_name = "";
        $first_name_position = 0;

        while ( isset( $first_name[$first_name_position] ) ) {
            $first_name_character = $first_name[$first_name_position];

            if ( $first_name_character == "\\" ) {
                $clean_first_name .= "\\\\";
            } else if ( $first_name_character == "'" ) {
                $clean_first_name .= "\\'";
            } else {
                $clean_first_name .= $first_name_character;
            }

            $first_name_position++;
        }

        $first_name = $clean_first_name;
        $clean_last_name = "";
        $last_name_position = 0;

        while ( isset( $last_name[$last_name_position] ) ) {
            $last_name_character = $last_name[$last_name_position];

            if ( $last_name_character == "\\" ) {
                $clean_last_name .= "\\\\";
            } else if ( $last_name_character == "'" ) {
                $clean_last_name .= "\\'";
            } else {
                $clean_last_name .= $last_name_character;
            }

            $last_name_position++;
        }

        $last_name = $clean_last_name;
        $clean_email = "";
        $email_position = 0;

        while ( isset( $email[$email_position] ) ) {
            $email_character = $email[$email_position];

            if ( $email_character == "\\" ) {
                $clean_email .= "\\\\";
            } else if ( $email_character == "'" ) {
                $clean_email .= "\\'";
            } else {
                $clean_email .= $email_character;
            }

            $email_position++;
        }

        $email = $clean_email;
        $clean_gender = "";
        $gender_position = 0;

        while ( isset( $gender[$gender_position] ) ) {
            $gender_character = $gender[$gender_position];

            if ( $gender_character == "\\" ) {
                $clean_gender .= "\\\\";
            } else if ( $gender_character == "'" ) {
                $clean_gender .= "\\'";
            } else {
                $clean_gender .= $gender_character;
            }

            $gender_position++;
        }

        $gender = $clean_gender;
        $clean_date_of_birth = "";
        $date_of_birth_position = 0;

        while ( isset( $date_of_birth[$date_of_birth_position] ) ) {
            $date_of_birth_character = $date_of_birth[$date_of_birth_position];

            if ( $date_of_birth_character == "\\" ) {
                $clean_date_of_birth .= "\\\\";
            } else if ( $date_of_birth_character == "'" ) {
                $clean_date_of_birth .= "\\'";
            } else {
                $clean_date_of_birth .= $date_of_birth_character;
            }

            $date_of_birth_position++;
        }

        $date_of_birth = $clean_date_of_birth;
        $clean_address = "";
        $address_position = 0;

        while ( isset( $address[$address_position] ) ) {
            $address_character = $address[$address_position];

            if ( $address_character == "\\" ) {
                $clean_address .= "\\\\";
            } else if ( $address_character == "'" ) {
                $clean_address .= "\\'";
            } else {
                $clean_address .= $address_character;
            }

            $address_position++;
        }

        $address = $clean_address;
	$check_sql = "SELECT user_id
	FROM user
	WHERE email = '$email'
	AND user_id != $user_id";

	$check_result = mysqli_query( $conn, $check_sql );

	if ( $check_result != false && mysqli_num_rows( $check_result ) > 0 ) {
		$_SESSION["user_error"] = "Another account is already using this email.";
		header( "Location: ../admin/users.php" );
		exit;
	}

	$image_sql = "";

	if ( isset( $_FILES["image"] ) && $_FILES["image"]["error"] == 0 ) {
		if ( $_FILES["image"]["size"] > 1048576 ) {
			$_SESSION["user_error"] = "Profile image must not be larger than 1 MB.";
			header( "Location: ../admin/users.php" );
			exit;
		}

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
			$_SESSION["user_error"] = "Only JPG, PNG and WebP images are allowed.";
			header( "Location: ../admin/users.php" );
			exit;
		}

		$new_image_name = time() . "." . $image_extension;
		$image_path = "assets/images/users/" . $new_image_name;
		$upload_path = "../" . $image_path;

		if ( move_uploaded_file( $_FILES["image"]["tmp_name"], $upload_path ) ) {
			$image_sql = ", user_image = '$image_path'";
		}
	}

	$sql = "UPDATE user SET
	first_name = '$first_name',
	last_name = '$last_name',
	email = '$email',
	gender = '$gender',
	date_of_birth = '$date_of_birth',
	address = '$address',
	is_active = '$db_status'
	$image_sql
	WHERE user_id = $user_id
	AND role_id = 2";

	$result = mysqli_query( $conn, $sql );

	if ( $result == true ) {
		$_SESSION["user_success"] = "User information updated successfully.";
	} else {
		$_SESSION["user_error"] = "User information could not be updated.";
	}

	header( "Location: ../admin/users.php" );
	exit;
}

header( "Location: ../admin/users.php" );
exit;


