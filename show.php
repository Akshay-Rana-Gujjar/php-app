<?php 

require_once("./connection_vars.php");

// Create connection
$conn = mysqli_connect($servername, $username,$password, $dbname);

// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}

$getImageQuery = "select url from `Stock` where category = 'test'";

$result = mysqli_query($conn, $getImageQuery);

while($row = $result->fetch_assoc()) {
    echo $row["url"];
    echo "<img src='".$row["url"]."'/>";
}

mysqli_close($conn);

?>