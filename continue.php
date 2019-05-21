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
Name of malware: <input type="text" name="mName"><br>
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

// first 20 bytes is virus signature


//
//   $filename = get_post($conn, 'file');
//   $text_contents = file_get_contents($filename);
//   $query = "SELECT * FROM viruses where seq LIKE '%text_contents%'" .
//   "(\"$file_contents\", $username)";
//   $result = $conn->query($query);
//   if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br>";
// }

// do shit with it

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
