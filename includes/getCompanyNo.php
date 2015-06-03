<?php

// test
// $companyName = "(aq) Limited";

$hasCleaned = FALSE;
$cleanedTwice = FALSE;
$originalName = NULL;
$api_key = "rbj4nzvr2jn934qtacurcwnm";

function getCompanyNo($companyName){

	// global $hasCleaned;
	// global $cleanedTwice;
	global $conn;
	global $originalName;

	if (!is_null($conn)){

		storeOriginal($companyName);
		$url = "http://api.duedil.com/open/search?q=" . $companyName . "&api_key=" . $api_key;
		$string = file_get_contents($url);

		if (!empty($string)){

			$regNo = getRegNofromExplode($string);
			updateInfo($originalName, $regNo);

		} else {
			echo "Could not find Registration Number.<br>";
			cleanAndTry ($companyName);
		}
	} else {
		die("Connection failed: " . $conn->connect_error);
	}
}


function cleanAndTry ($companyName){
	global $hasCleaned;
	global $cleanedTwice;
	global $clean;

	if (!$hasCleaned){

		echo "cleaning... <br>";
		$hasCleaned = TRUE;
		$clean = replaceWithDash($companyName, ' ');
		// echo "cleaned1 is " . $clean;
		getCompanyNo($clean);

	} else if ($hasCleaned && !$cleanedTwice) {

		echo "cleaning again... <br>";
		$cleanedTwice = TRUE;
		echo "cleaned is " . $clean;
		$clean2 = escape($clean);
		getCompanyNo($clean2);

	} else {

		echo "Despite our efforts, could not find the registration number.<br>";
		updateInfo($originalName, 1); //insert 1 for searched but not found.
		$hasCleaned = FALSE;
		$cleanedTwice = FALSE;

	}
}

function storeOriginal ($name){
	global $originalName;
	global $hasCleaned;

	if (!$hasCleaned) {
		$originalName = $name;
		echo $originalName . ": original name.<br>";
	}
}

function escape($companyName){
	global $conn;

	if(!preg_match('/^\[a-zA-Z]+$/', $companyName)) {
   // String contains not allowed characters ...
		echo "Special case for " . $companyName . " ... <br>";
		// $companyName = $conn->real_escape_string($companyName);
		// $companyName = strtolower($companyName);
		$companyName = preg_replace('/[^a-zA-Z0-9 -]+/', '', $companyName);
		echo "Result: " . $companyName . "<br>Escaped!<br>";
		return $companyName;
	} else {
		return $companyName;
		echo "Did not escape!";
	}
}

function replaceWithDash($text, $getRidOf){
	return str_replace($getRidOf, '-', $text);
}

function updateInfo($companyName, $regNo){
	global $conn;
	// $sqlCompanyName = "\'". $companyName ."\'";
	
	echo $companyName . "<br>";
	// working: UPDATE `Organisation` SET `RegNo`=04038783 WHERE `OrganisationName`='1000heads Ltd'
	$sql = $conn->prepare("UPDATE Organisation SET RegNo=? WHERE OrganisationName=?");

	if (!$sql) {
		die ('mysqli error: '.mysqli_error($connection));
	}

	$sql->bind_param('is', $regNo, $companyName);

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

function checkAllNames(){
	global $string;
//for all the items check using checkifmatchingcompany
}

function matchingCompany($companyName, $givenName){
	//if company name == given name return true.

}

function getPostCode($regNo){
	global $api_key;
	$string = file_get_contents("http://api.duedil.com/open/uk/company/" . $regNo . "?api_key=" . $api_key);
	print_r($string);
//explode

}

function findCounty($postcode){
	$string = file_get_contents("https://api.postcodes.io/postcodes/:" . $postcode);
	print_r($string);
//explode admin_county

}

function findCountry($postcode){
	$string = file_get_contents("https://api.postcodes.io/postcodes/:" . $postcode);
print_r($string);
//explode country
}

?>