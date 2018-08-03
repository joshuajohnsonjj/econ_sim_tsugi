<?php
/* index.php

- Simply redirects to proper UI based on instructor/student status.

Last Update: remove sql for Users table
*/
include "src/utils/sql_settup.php";
require_once "../config.php";

use \Tsugi\Core\LTIX;

$LAUNCH = LTIX::requireData();

// check if user is a student or instructor
if ( !$USER->instructor ) // redirect to student UI
    header("Location: ".addSession("src/student.php")); 
 else  // if instructor redirect to admin UI
    header("Location: ".addSession("src/admin_page.php"));
?>
