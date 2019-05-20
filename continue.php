<?php
session_start();
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $isAdmin = $_SESSION['isAdmin'];
echo <<< _END
<!DOCTYPE html>
<html>
<head><title>Virus Checker</title></head>
<body>
<form method="post" action="continue.php">
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


function destroy_session_data() {
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

function mysql_fix_string($connection, $string) {
    if(get_magic_quotes_gpc())
        $string = stripslashes($string);
    return $connection->real_escape_string($string);
}

function get_post($conn, $var) {
  return $conn->real_escape_string($_POST[$var]);
}
?>
