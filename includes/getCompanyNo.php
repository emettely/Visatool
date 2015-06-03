<?php

// test
// $companyName = "(aq) Limited";

$hasCleaned = FALSE;
$cleanedTwice = FALSE;
$originalName = NULL;

function getCompanyNo($companyName){

	// global $hasCleaned;
	// global $cleanedTwice;
	global $conn;
	global $originalName;

	if (!is_null($conn)){

		storeOriginal($companyName);
		$url = "http://api.duedil.com/open/search?q=" . $companyName . "&api_key=rbj4nzvr2jn934qtacurcwnm";
		$string = file_get_contents($url);

		if (!empty($string)){

			$regNo = getRegNofromExplode($string);

			updateInfo($originalName, $regNo);

		} else {
			echo "Could not find Registration Number.";
			cleanAndTry ($companyName);
		}
	} else {
		die("Connection failed: " . $conn->connect_error);
	}
}


function cleanAndTry ($companyName){
	global $hasCleaned;
	global $cleanedTwice;

	if (!$hasCleaned){

		echo "cleaning... <br>";
		$hasCleaned = TRUE;
		$clean = removeSpace($companyName);
		getCompanyNo($clean);

	} else if ($hasCleaned && !$cleanedTwice) {

		echo "cleaning again... <br>";
		$cleanedTwice = TRUE;
		$clean2 = escape($clean);
		getCompanyNo($clean2);

	} else {

		echo "Despite our efforts, could not find the registration number.<br>";
		$hasCleaned = FALSE;
		$cleanedTwice = FALSE;

	}
}

function storeOriginal ($name){
	global $originalName;
	global $hasCleaned;

	if (!$hasCleaned) {
		$originalName = $name;
		echo $originalName . "original Name here: <br>";
	}
}

function escape($companyName){
	global $conn;

	if(!preg_match('/^\[a-zA-Z]+$/',$input)) {
   // String contains not allowed characters ...
		echo "Special case ... <br>";
		$companyName = $conn->real_escape_string($companyName);
		echo "Escaped!<br>";
		return $companyName;
	} else {
		return $companyName;
		echo "Did not escape!";
	}
}

function removeSpace($text){
	return str_replace(' ', '', $text);
}

function updateInfo($companyName, $regNo){
	global $conn;
	$sqlCompanyName = "\"". $companyName ."\"";
	echo $sqlCompanyName . "<br>";
	$sql = $conn->prepare("UPDATE Organisation SET RegNo =? WHERE OrganisationName =?");

	if (!$sql) {
		die ('mysqli error: '.mysqli_error($connection));
	}

	$sql->bind_param('is', $regNo, $sqlCompanyName);

	if (!mysqli_execute($sql)) {
		die('stmt error: '.mysqli_stmt_error($sql));
	}

	if ($sql->execute() === TRUE) {
		echo "Record updated successfully " . $regNo . "<br>";
		return $regNo;

	} else {
		echo "Error updating record: " . $conn->error;
	}
}

function getRegNofromExplode($string){
	$lines = explode(",", $string);
	preg_match_all('!\d+!', $lines[1], $companyNo);
	$regNo = $companyNo[0][0];
	return $regNo;
}

?>