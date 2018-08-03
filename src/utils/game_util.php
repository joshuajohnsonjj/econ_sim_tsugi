<?php
/* add_game.php

- handles saving game/deleting from mysql database.
- When saving, checks if game exists alreay to decide wheter to save as new,
or update existing.\

Last Update:
*/
ini_set('display_errors', 1); error_reporting(-1);
include 'sql_settup.php';
require_once "../../../config.php";

// Create/save game game
if (isset($_POST['mode']) && isset($_POST['difficulty']) && isset($_POST['market_struct']) 
	&& isset($_POST['macroEconomy']) && isset($_POST['limit']) 
	&& isset($_POST['numRounds']) && isset($_POST['course_id']) && isset($_POST['gameName'])) {
	
	// get max value for quantity, in order to keep price/demand positive
	switch ($_POST['market_struct']) {
		case 'monopoly': 
			$max=($_POST['demand_intercept']/$_POST['demand_slope'])-1;
			break;
		case 'oligopoly':
			$max=(($_POST['demand_intercept']/$_POST['demand_slope'])/2)-1;
			break;
		case 'monopolistic':
		case 'perfect':
			$max=500;
			break;
	}
	
	// check if game already exists, if so, update rather than create new
	if ($_POST['gameId']) {
		$sql = "UPDATE Games SET name='".mysqli_real_escape_string($mysqli, $_POST['gameName'])."', difficulty='".$_POST['difficulty']."', mode='".$_POST['mode']."', market_struct='".$_POST['market_struct']."', macro_econ='".$_POST['macroEconomy']."', time_limit='".$_POST['limit']."', num_rounds='".$_POST['numRounds']."', demand_intercept='".$_POST['demand_intercept']."', demand_slope='".$_POST['demand_slope']."', fixed_cost='".$_POST['fixed_cost']."', const_cost='".$_POST['const_cost']."', max_quantity='".$max."' WHERE course_id=".$_POST['course_id'];
		if ($mysqli->query($sql) === TRUE) {
			$url = "../../src/admin_page.php?game=".$_POST['gameId'];
			header("Location: ".addSession($url));
		}
		else 
		    echo "Error: " . $sql . "<br>" . $mysqli->error;
	}
	else {
		$sql = "INSERT INTO Games (name, type, course_id, difficulty, mode, market_struct, macro_econ, time_limit, num_rounds, demand_intercept, demand_slope, fixed_cost, const_cost, max_quantity)
		VALUES ('".mysqli_real_escape_string($mysqli, $_POST['gameName'])."', '".$_POST['type']."', '".$_POST['course_id']."', '".$_POST['difficulty']."', '".$_POST['mode']."', '".$_POST['market_struct']."', '".$_POST['macroEconomy']."', '".$_POST['limit']."', '".$_POST['numRounds']."', '".$_POST['demand_intercept']."', '".$_POST['demand_slope']."', '".$_POST['fixed_cost']."', '".$_POST['const_cost']."', '".$max."')";
		if ($mysqli->query($sql) === TRUE) {
			$url = "../../src/admin_page.php?course=".$_POST['course_id'];
			header("Location: ".addSession($url));
		}
		else 
		    echo "Error: " . $sql . "<br>" . $mysqli->error;
	}	
}

// Delete game
else if (isset($_POST['deleteId']) && isset($_POST['deletedGameCourse'])) { 
	$delete_sql = "DELETE FROM Games WHERE id='".$_POST['deleteId']."'";

	if ($mysqli->query($delete_sql) === TRUE) {
		$url="../../src/admin_page.php?course=".$_POST['deletedGameCourse'];
		header("Location: ".addSession($url));
	}
	else 
	    echo "Error: " . $delete_sql . "<br>" . $mysqli->error;
}
