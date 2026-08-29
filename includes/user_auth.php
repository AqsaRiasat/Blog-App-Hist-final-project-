<?php

require __DIR__ . "/session.php";

if ( !isset( $_SESSION["user_id"] ) ) {
	header( "Location: login.php" );
	exit;
}


