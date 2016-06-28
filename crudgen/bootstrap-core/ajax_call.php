 <?php
 $table_name = $_REQUEST["q"];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "arbac_trams";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT *
FROM information_schema.columns
WHERE  table_name = '".$table_name."'
   AND table_schema = 'arbac_trams'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	
    while($row = $result->fetch_array()) {
        $res[] = $row;
    }
} else {
    $res= 0;
}
echo json_encode($res);
$conn->close();
?> 