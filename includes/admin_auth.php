<?php

require __DIR__ . "/session.php";

if ( !isset( $_SESSION["user_id"] ) ) {
	header( "Location: ../login.php" );
	exit;
}

if ( $_SESSION["role_type"] != "Admin" && $_SESSION["role_type"] != "Administrator" ) {
	header( "Location: ../index.php" );
	exit;
}


