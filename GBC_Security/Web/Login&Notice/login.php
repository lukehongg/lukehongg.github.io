<?php
$servername = "localhost";
$myusername = "root";
$mypassword = "password";
$dbname = "user_info";

$conn = mysqli_connect($servername, $myusername, $mypassword, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, password FROM user";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    if($row["id"] == $_POST["userid"] && $row["password"] == $_POST["password"]){
      header('location: login_OK.php');
    }
  }
} else {
  echo "login failed";
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<body>

<h2>HTML Forms</h2>

<form action="./login.php" method="post">
  <label for="userid">ID:</label><br>
  <input type="text" id="userid" name="userid"><br>
  <label for="pw">pw:</label><br>
  <input type="password" id="password" name="password"><br><br>
  <input type="submit" value="Submit">
</form> 

<p>ID, pw 입력해주세요.</p>

</body>
</html>

