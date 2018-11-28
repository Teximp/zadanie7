<!DOCTYPE HTML>
<head>
	<meta charset="utf_8"/>
	<title="Gerc"/>
	<style>
	#panel
	{
	text-align: center 
	}
	</style>
</head>
<body>

<?php
	$dbhost="serwer1887918.home.pl"; $dbuser="28891316_z7"; $dbpassword="8363277a"; $dbname="28891316_z7";
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	if (!$polaczenie) {
	echo "Błąd połączenia z MySQL." . PHP_EOL;
	echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Error: " . mysqli_connect_error() . PHP_EOL;
	exit;
	}

 $rejestruj = $_POST['rejestruj'];
	if (isset($rejestruj))
	{
	   $login = $_POST['login'];
	   $haslo1 =md5($_POST['haslo1']);
	   $haslo2 = md5($_POST['haslo2']);
	   $ans = mysqli_query($polaczenie,"SELECT nazwisko FROM klienci WHERE nazwisko = '$login' ");
		$bans = mysqli_num_rows ($ans);
	   if ($bans == 0)
	   {
		  if ($haslo1 == $haslo2)
		  {
			 $ins = mysqli_query($polaczenie,"INSERT INTO klienci VALUE ('','$login','$haslo1')");
	 
			 echo "Konto zostało utworzone!";
			 
			$folder = $_POST['login'];
			mkdir ("../z7/$folder", 0777);
			echo "<br /><b>Folder o nazwie: <i>$folder</i> został stworzony!</b>";
		  }
		   else 
			   echo '<div id="panel">';
			  echo "Hasła nie są takie same";
			  echo '<div id="panel">';
	   }
	   else 
		   echo '<div id="panel">';
		   echo "Podany login jest już zajęty.";
			echo '<div id="panel">';
	}
?>




<div id="panel">

<form method="POST" action="<?php echo $_SERVER['PHP_self']; ?>">
<b>Login:</b> <input type="text" name="login"><br><br>
<b>Hasło:</b> <input type="password" name="haslo1"><br><br>
<b>Powtórz hasło:</b> <input type="password" name="haslo2"><br><br>
<input type="submit" value="Utwórz konto" name="rejestruj">
</form>
<br /> <br />
<a href = "index.php" target="_blank"> Przejdz do strony logowania </a>
</div>
	
</body>
</html>