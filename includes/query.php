<?php

$sql = "SELECT DISTINCT (OrganisationName) AS Name FROM Organisation WHERE Name LIKE '%[^0-9a-zA-Z]%' COLLATE Latin1_General_BIN ORDER BY Name ASC";
$result = $conn->query($sql);

// echo "?What If! Ltd";
if ($result->num_rows > 0) {
	// if ($result = $conn->use_result()){
    // output data of each row
	while($row = $result->fetch_assoc()) {
    	// print_r($row);
        echo "";
		// echo $row["Name"] . "<br>";
		// getCompanyNo($row["Name"]);		
	}
	// }
} else {
	echo "0 results";
}
?>