<?php
include "src/utils/sql_settup.php";
require_once "../config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::session_start();

// if ( !$LAUNCH->user->instructor ) { // if student add to user table and redirect to student UI
// 	$result = $mysqli->query("SELECT * FROM Users WHERE email='".$LAUNCH->user->email."'");
// 	if ($result->num_rows == 0) { // User does not exist in database table so add it
// 		$add_usr_sql = "INSERT INTO Users (email) VALUES ('".$LAUNCH->user->email."')";
// 		$mysqli->query($add_usr_sql);
// 	}
//     header("Location: ".addSession("src/student.php"));
// } else { // if instructor redirect to admin UI
//     header("Location: ".addSession("src/admin_page.php"));
// }
?>

<script src="js/vendor/jquery.js"></script>
<script type="text/javascript">
jQuery.get("cgi-bin/test, function(response) { 
	var_dump(response);
});
</script>	

