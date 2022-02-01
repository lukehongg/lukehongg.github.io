<?php
$servername = "localhost";
$myusername = "root";
$mypassword = "password";
$dbname = "user_info";

$conn = mysqli_connect($servername, $myusername, $mypassword, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM notices";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "Writer: " . $row["writer"]. " - Contents: " . $row["contents"]. "<br>";
  }
} else {
  echo "No notices";
}
mysqli_close($conn);
?>