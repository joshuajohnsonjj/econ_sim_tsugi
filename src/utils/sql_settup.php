<?php
/* sql_settup.php

- Contains initial setup for the mysql tables in database. Commented out after the first run. 
- Below are a handfull of utility functions relating to the mysql database.

GameSessionData holds the data for each round for every student. Each student will have their own row for each game that they play. The Sessions table is used to help with multi player games, there will be one row for a session (not for each student) - this table simply holds the groupId and the two players 

Last Update:
8.1.18
Add Sessions table 
*/

DEFINE('DB_USERNAME', 'root');
DEFINE('DB_PASSWORD', 'root');
DEFINE('DB_HOST', 'localhost');
DEFINE('DB_DATABASE', 'econ_sim_data');

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (mysqli_connect_error()) {
	die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
}

// INITIALIZES TABLES
// =======================
// $usertbl = "CREATE TABLE Users (
// 	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	email VARCHAR(30) NOT NULL,
//  in_session INT(6) DEFAULT NULL,
//  opponent VARCHAR(15) DEFAULT NULL,
// 	reg_date TIMESTAMP
// )";
// if ($mysqli->query($usertbl) === TRUE) {
// 	echo "make table success";
// } else {
// 	echo 'failed: users ';
// }
// $coursetbl = "CREATE TABLE Courses (
// 	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	name VARCHAR(30) NOT NULL,
// 	section VARCHAR(30) NOT NULL,
// 	owner VARCHAR(30) NOT NULL,
// 	avatar VARCHAR(30) DEFAULT 'fa-chart-bar',
// 	reg_date TIMESTAMP
// )";
// if ($mysqli->query($coursetbl) === TRUE) {
// 	echo "make courses table success";
// } else {
// 	echo 'failed: courses ';
// }
// // gamestbl contains setup info for all created games
// $gamestbl = "CREATE TABLE Games (
// 	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	name VARCHAR(30) NOT NULL,
//  	live BOOLEAN DEAFAULT FALSE,
// 	type VARCHAR(30) NOT NULL,
// 	course_id VARCHAR(30) NOT NULL,
// 	difficulty VARCHAR(30) NOT NULL,
// 	mode VARCHAR(30) NOT NULL,
// 	market_struct VARCHAR(30) NOT NULL,
// 	macro_econ	VARCHAR(30) NOT NULL,
// 	rand_events BOOLEAN NOT NULL,
// 	time_limit INT(6) NOT NULL,
// 	num_rounds INT(6) NOT NULL,
// 	demand_intercept INT(6) NOT NULL,
// 	demand_slope INT(6) NOT NULL,
// 	fixed_cost INT(6) NOT NULL,
// 	const_cost INT(6) NOT NULL,
// 	equilibrium INT(6) DEFAULT NULL,
// 	reg_date TIMESTAMP
// )";
// if ($mysqli->query($gamestbl) === TRUE) {
// 	echo "make games table success";
// } else {
// 	echo 'failed: games ';
// }
// // gameCollection contains all live game sessions
// $gameCollection = "CREATE TABLE GameCollection (
// 	id 				INT(6)			UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	groupId			INT(20)			NOT NULL,
// 	game_num		INT(6)			NOT NULL,
// 	num_players 	INT(1)			NOT NULL,
// 	player1 		VARCHAR(30) 	DEFAULT NULL,
// 	player1_data 	VARCHAR(300) 	DEFAULT NULL,
// 	player2 		VARCHAR(30) 	DEFAULT NULL,
// 	player2_data 	VARCHAR(300) 	DEFAULT NULL,
// 	player3 		VARCHAR(30) 	DEFAULT NULL,
// 	player3_data 	VARCHAR(300) 	DEFAULT NULL,
// 	full 			BOOLEAN			DEFAULT FALSE
// 	)";
// if ($mysqli->query($gameCollection) === TRUE) {
// 	echo "make gameCollection success";
// } else {
// 	echo 'failed: gameCollection ';
// }
// // gameSessionData contains data all current or finished game sessions (one entry for each player)
// $gameSessionData = "CREATE TABLE GameSessionData (
// 	id 					INT(6)			UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	complete 			BOOLEAN			DEFAULT FALSE,
// 	groupId				INT(20)			NOT NULL,
// 	player 				VARCHAR(30) 	NOT NULL,
// 	opponent 			VARCHAR(30) 	DEFAULT NULL,
// 	player_quantity 	VARCHAR(300) 	NOT NULL,
// 	player_profit 		VARCHAR(300) 	NOT NULL,
// 	player_revenue 		VARCHAR(300) 	NOT NULL,
// 	player_return 		VARCHAR(300) 	NOT NULL,
// 	price 				VARCHAR(300)	NOT NULL,
// 	unit_cost 			VARCHAR(300)	NOT NULL,
// 	total_cost			VARCHAR(300)	NOT NULL
// 	)";
// if ($mysqli->query($gameSessionData) === TRUE) {
// 	echo "make gameSessionData success";
// } else {
// 	echo 'failed: gameSessionData ';
// }
// // Sessions contains all live game sessions
// $sessions = "CREATE TABLE Sessions (
// 	id 					INT(6)			UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	groupId				INT(20)			NOT NULL,
//	gameId 				Int(6)			NOT NULL,
// 	p1 					VARCHAR(30) 	DEFAULT NULL,
//	p1Data 				INT(20)			DEFAULT NULL,
// 	)";
// if ($mysqli->query($sessions) === TRUE) {
// 	echo "make Sessions success";
// } else {
// 	echo 'failed: Sessions ';
// }
// =======================


// UTILITY FUNCTIONS
// =================
// Get instructor's saved courses from Courses table
function getCourses($mysqli, $usr) {
	$result = $mysqli->query("SELECT * FROM Courses");
	$courses = [];

	if($result === FALSE)
		die("ERROR! Can't get courses."); 

	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			if ($usr == $row["owner"])
				array_push($courses, $row);
	return $courses;
}

// for games screen get course name and section
function getCourseNameSection($mysqli, $id) {
	$result = $mysqli->query("SELECT name, section FROM Courses WHERE id=".$id);
	$info = [];

	if($result === FALSE)
		die("ERROR! Can't get course info."); 

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		array_push($info, $row['name']);
		array_push($info, $row['section']);
	}
	return $info;
}

// get course's saved games & info
// --------------
function getGames($mysqli, $course) {
	// return the games contained by a specified course
	$result = $mysqli->query("SELECT * FROM Games");
	$games = [];

	if($result === FALSE)
		die("ERROR! Can't get games."); 

	if ($result->num_rows > 0)
		while ($row = $result->fetch_assoc())
			if ($course == $row["course_id"])
				array_push($games, $row);
	return $games;
}

// return the details for a specified game
function getGameInfo($mysqli, $game) {
	$result = $mysqli->query('SELECT * FROM Games WHERE id='.$game);

	if($result === FALSE)
		die("ERROR! Can't get game info."); 

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		return $row;
	}
}
// ---------------

// return boolean - game session is "live" (joinable by students) or not 
function sessionIsLive($mysqli, $id) {
	return $mysqli->query('SELECT live FROM Games WHERE id="'.$id.'" LIMIT 1')->fetch_assoc()['live'];
}
