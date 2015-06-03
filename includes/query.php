<?php

global $conn;

$sql = "SELECT DISTINCT (OrganisationName) AS Name, RegNo FROM Organisation ORDER BY Name ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
		print_r($row);
        // echo "";
		echo $row["Name"] . " ";
		echo $row["RegNo"] . "<br>";
		if ($row["RegNo"]== 0) {
			getCompanyNo($row["Name"]);
		}
	}
} else {
	echo "0 results";
}

?>