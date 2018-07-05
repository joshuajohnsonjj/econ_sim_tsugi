<?php
include "src/utils/sql_settup.php";
require_once "../config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::session_start();
var_dump($LAUNCH->user);
if ( !$LAUNCH->user->instructor ) { // if student add to user table and redirect to student UI
	$result = $mysqli->query("SELECT * FROM Users WHERE email='".$LAUNCH->user->email."'");
	if ($result->num_rows == 0) { // User does not exist in database table so add it
		$add_usr_sql = "INSERT INTO Users (email) VALUES ('".$LAUNCH->user->email."')";
		$mysqli->query($add_usr_sql);
	}
    header("Location: src/student.php");
} else { // if instructor redirect to admin UI
//     header("Location: src/admin_page.php");
    var_dump("Instructor status: ".$LAUNCH->user->instructor);
}

?>

