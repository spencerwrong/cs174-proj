<?php
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $isAdmin = $_SESSION['isAdmin'];

//Frontend
echo <<< _END
<!DOCTYPE html>
<html>
<head><title>Virus Checker</title></head>
<body>
<form enctype="multipart/form-data" method="post" action="continue.php">
Select Text File: <input type="file" name="file"><br>
_END;

    if($isAdmin == 1) {

echo <<< _END
Upload Virus Signature<br>
Name of malware: <input type="text" name="malware_name"><br>
<input type="submit"><br>
</form></body></html>
_END;

    }

    else {

echo <<<_END
Upload Suspected File<br>
<input type="submit">
</form></body><html>
_END;

    }
}
else
    echo "Please <a href=authenticate.php>click here</a> to log in.";
// END Frontend

// Get File Contents
  $text_contents = file_get_contents($_FILES['file']['tmp_name']);
  $m_name = mysql_entities_fix_string($conn, $_POST['malware_name']);

// first 20 bytes is virus signature

//logic : if you find the signature already, dont upload again
// fix logic
if($isAdmin == 1)
{
  $query = "SELECT * FROM viruses WHERE seq='$text_contents'";
  $result = $conn->query($query);
  if (!$result)
  {
    $query = "INSERT INTO viruses (name, seq) VALUES ('$m_name', '$text_contents')";
    $result = $conn->query($query);
    if(!$result) die ("Data access failed: ".$conn->error);
    else echo "Virus signature has been uploaded!";
  }
  else {
    echo "Virus already in database.";
  }
}

// fix logic
else { //isadmin == 0 if you find string sequence in seq column, contains a virus
  $query = "SELECT * FROM viruses WHERE seq LIKE '%$text_contents%'";
  $result = $conn->query($query);
  if(!$result) die ("Data access failed: " . $conn->error);
  else {
    if($result->num_rows == 0) {
      echo "no virus detected";
    }
    else {
      echo "virus detected";
    }
  }
}

// Functions
function destroy_session_data() {
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn, $string));
}

function mysql_fix_string($conn, $string) {
    if(get_magic_quotes_gpc())
        $string = stripslashes($string);
    return $conn->real_escape_string($string);
}

function get_post($conn, $var) {
  return $conn->real_escape_string($_POST[$var]);
}
?>
