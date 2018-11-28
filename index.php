<?php

	$dbhost="serwer1887918.home.pl"; $dbuser="28891316_z7"; $dbpassword="8363277a"; $dbname="28891316_z7";
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	if (!$polaczenie) {
	echo "Błąd połączenia z MySQL." . PHP_EOL;
	echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Error: " . mysqli_connect_error() . PHP_EOL;
	exit;
	}
	
	$ip = $_SERVER["REMOTE_ADDR"];
	$result2 = mysqli_query($polaczenie, "SELECT COUNT(*) FROM `logi` WHERE `address` LIKE '$ip' AND (CURRENT_TIMESTAMP - `nieudane`) < 120");
	$count1 = mysqli_fetch_array($result2, MYSQLI_NUM);
	
if ($count1[0] < 3){	
	if (isset($_POST['loguj'])) {
		$user=$_POST['login']; // login z formularza
		$pass=$_POST['haslo']; // hasło z formularza
		$result = mysqli_query($polaczenie, "SELECT * FROM klienci WHERE nazwisko='$user'"); // pobranie z BD wiersza, w którym login=login z formularza
		$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
		
		
		if(!$rekord) //Jeśli brak, to nie ma użytkownika o podanym loginie
			{
			mysqli_close($polaczenie); // zamknięcie połączenia z BD
			echo "Brak użytkownika o takim loginie !"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
			}
		else
		{ // Jeśli $rekord istnieje
			if($rekord['haslo']==md5($pass)) // czy hasło zgadza się z BD
			{
			$ip = $_SERVER["REMOTE_ADDR"];
			$result = mysqli_query($polaczenie, "SELECT * FROM klienci WHERE nazwisko='$user'");
			while ($wiersz = mysqli_fetch_array ($result)) {
			$idk = $wiersz[0];
			}
			$logklienta = mysqli_query($polaczenie,"INSERT INTO logi VALUES ('','$idk',CURRENT_TIMESTAMP,'','$ip')");
			setcookie('imie',$user,time()+1200);
			header("Location: index3.php");
			}
			else
			{
			$ip = $_SERVER["REMOTE_ADDR"];
			$result = mysqli_query($polaczenie, "SELECT * FROM klienci WHERE nazwisko='$user'");
			while ($wiersz = mysqli_fetch_array ($result)) {
			$idk = $wiersz[0];
			}
			$iplog = mysqli_query($polaczenie, "INSERT INTO `logi` (`idk`,`udane`,`nieudane`,`address`) VALUES ('$idk','',CURRENT_TIMESTAMP,'$ip')");
			echo '<div id="panel">';
			echo "Błąd w haśle !"; // UWAGA nie wyświetlamy takich podpowiedzi dla hakerów
			echo '</div>';
			}
		}
	}
}
else
{
	echo '<div id="panel">';
	echo (" <font color='red'><b> Twoj adres IP został zablokowany na 2 minuty.</b></font>");
	echo '</div>';
}	

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf_8"/>
	<title="Gerc"/>
	<meta http-equiv="refresh" content="20" />
	<style>
	#panel
	{
	text-align: center 
	}
	</style>
</head>
<body>

<div id="panel">
<form method="POST" action="<?php echo $_SERVER['PHP_self']; ?> ">
<b>Login:</b> <input type="text" name="login"><br /><br />
<b>Hasło:</b> <input type="password" name="haslo"><br /><br />
<input type="submit" value="Zaloguj" name="loguj">
</form>
<br />
Jeżeli nie masz konta <a href="index2.php" target="_blank"> Zarejestruj się </a>
<br />
<br />
<a href="analiza.pdf" target="_blank"> Analiza kodu i szybkosci portalu </a>
<br />
<a href="dokumentacja.pdf" target="_blank"> Dokumentacja bazy danych </a>
<br />
<a href="raport.html" target="_blank"> Analiza programem OWASP </a>
<br />
<br />
<br />
Analiza portalu programem OWASP błednie wykryła zagrożenie SQL INJECTION w index2.php, ponieważ jest to formularz rejestracji a nie logowania. 
</div>
</body>
</html>