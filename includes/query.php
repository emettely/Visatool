<?php

$sql = "SELECT DISTINCT (CityTown) AS CityTown FROM Organisation ORDER BY CityTown ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Location: " . $row["Location"]. "<br>";
    }
} else {
    echo "0 results";
}

?>