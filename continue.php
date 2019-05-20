<?php //better continue.php
	session_start();
	require_once 'login.php';
	$conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

	function get_post($conn, $var)
	{
		return $conn->real_escape_string($_POST[$var]);
	}

	if (isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
		$forename = $_SESSION['forename'];
		$surname = $_SESSION['surname'];

		echo <<<_END
		<form action="continue.php" method="post"><pre>
		File <input type="file" name="file">
		Text <input type="text" name="text">
		<input type="submit" value="ADD RECORD">
		</pre></form>
		<a href="logout.php">Logout</a>
		_END;

		if (isset($_POST['file']))
    {
      $filename = get_post($conn, 'file');
      $file_contents = file_get_contents($filename);
      $query = "INSERT INTO content VALUES" .
      "(\"$file_contents\", $username)";
      $result = $conn->query($query);
    	if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br>";
    }

    if (isset($_POST['text']))
    {
      $text_contents = get_post($conn, 'text');
      $query = "INSERT INTO content VALUES" .
      "(\"$text_contents\", \"$username\")";
      $result = $conn->query($query);
    	if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br>";
    }

		$query = "SELECT * FROM content";
	  $result = $conn->query($query);
	  if (!$result) die ("Database access failed: " . $conn->error);

	  $rows = $result->num_rows;
	  for ($j = 0 ; $j < $rows ; ++$j)
	  {

	  	$result->data_seek($j);
	  	$row = $result->fetch_array(MYSQLI_NUM);
			if($username == $row[1])
			{
				echo <<<_END
		      <pre>
		      Text $row[0]
		      </pre>
		_END;
			}
	  }
	}
	else echo "Please <a href='authenticate.php'>click here</a> to log in.";



?>
