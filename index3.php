<?php
$ciastko= $_COOKIE['imie'];
if (isset($_POST['wylogowanie'])) {
setcookie ("imie", "", time() - 600);
   header("Location: index.php");
}
if(isset($_POST['pobierz']))
{
$file = $_POST['cos']; 
    //First, see if the file exists
    if (!is_file($file)) { die("<b>404 File not found!</b>"); } 
    //Gather relevent info about file
    $len = filesize($file);
    $filename = basename($file);
    $file_extension = strtolower(substr(strrchr($filename,"."),1)); 
    //This will set the Content-Type to the appropriate setting for the file
    switch( $file_extension ) {
          case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "mp3": $ctype="audio/mpeg"; break;
      case "wav": $ctype="audio/x-wav"; break;
      case "mpeg":
      case "mpg":
      case "mpe": $ctype="video/mpeg"; break;
      case "mov": $ctype="video/quicktime"; break;
      case "avi": $ctype="video/x-msvideo"; break;
 
      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
      case "php":
      case "htm":
      case "html":
      case "txt": $ctype="text/plain"; break;
 
      default: $ctype="application/force-download";
    }
    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");  
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    @$header="Content-Disposition: attachment; filename=".$filename.";";
    header($header );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    @readfile($file);
    exit;
}
$dbhost="serwer1887918.home.pl"; $dbuser="28891316_z7"; $dbpassword="8363277a"; $dbname="28891316_z7";
	$polaczenie = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);
	if (!$polaczenie) {
	echo "Błąd połączenia z MySQL." . PHP_EOL;
	echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
	echo "Error: " . mysqli_connect_error() . PHP_EOL;
	exit;
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf_8"/>
	<title="Gerc"/>
	<meta http-equiv="refresh" content="20" />
		<style>
	#srodek
	{
	text-align: center 
	}
	</style>
</head>	
<body>
<?php
			$result = mysqli_query($polaczenie, "SELECT * FROM klienci WHERE nazwisko='$ciastko'");
			while ($wiersz = mysqli_fetch_array ($result)) {
			$idk = $wiersz[0];
			}
$blednelogowanie = mysqli_query($polaczenie, "SELECT * FROM logi WHERE idk='$idk' ORDER BY id DESC limit 2");
while ($log = mysqli_fetch_array ($blednelogowanie)) {
		$log1 = $log[3];
		}
		
	if($log1 != "0000-00-00 00:00:00"){
	echo '<div id="srodek">';	
	echo ("<font color='red'><b>Ostatnie bledne logowanie : $log1 </font></b> <br>");
	echo '</div>';
	}
?>
<div id="srodek">
Stworz katalog
<form action="<?php echo $_SERVER['PHP_self']; ?> " method="POST" ENCTYPE="multipart/form-data">
<input type="text" name="nazwa"/>
<input type="submit" value="Stworz Katalog" name="wyslij1"/>
</form>

<br />
<?php
			if(isset($_POST['wyslij1'])){
			$folder = $_POST['nazwa'];
			mkdir ("../z7/$ciastko/$folder/", 0777);
			echo "<br />Folder o nazwie: <i>$folder</i> został stworzony!<br />";
			}
?>
Lista plików i folderów jakie posiadasz:
<br />
<?php
$objDir = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( "./$ciastko/" ) );

foreach( $objDir as $objFile )
{
	echo $objFile . '<br>';
	
}
?>
<br />
<br />

Wybierz folder do, którego chcesz udostępnić plik:
<br />
<form action="<?php echo $_SERVER['PHP_self']; ?> " method="POST" ENCTYPE="multipart/form-data">
<select name="folder">
 <option value="<?php echo "$ciastko" ?>"><?php echo "$ciastko" ?></option>
<?php
chdir("./$ciastko/");
foreach (glob("*",GLOB_ONLYDIR) as $folder3) {
    if($folder3!="FILES"){
	echo "<option value ='".$folder3."'>".$folder3."</option>";
}
}
?>
</select>
<br />
<br />
<input type="file" name="plik"/>
<br />
<br />
<input type="submit" value="Wyślij" name="wyslij"/>
</form>

<br />
<?php
$folder= $_POST['folder'];
if($_POST['folder'] == "$ciastko"){
 $plik= $_POST['plik'];
    if (isset($_POST['wyslij'])){
$max_rozmiar = 1000000;
if (is_uploaded_file($_FILES['plik']['tmp_name']))
{
if ($_FILES['plik']['size'] > $max_rozmiar) {echo "Przekroczenie rozmiaru $max_rozmiar"; }
else
{	
echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';
if (isset($_FILES['plik']['type'])) {echo 'Typ: '.$_FILES['plik']['type'].'<br/>'; }
move_uploaded_file($_FILES['plik']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."../z7/$ciastko/".$_FILES['plik']['name']);
}
}
else {echo 'Błąd przy przesyłaniu danych!';}
}
}
else
{
 $plik= $_POST['plik'];
    if (isset($_POST['wyslij'])){
$max_rozmiar = 1000000;
if (is_uploaded_file($_FILES['plik']['tmp_name']))
{
if ($_FILES['plik']['size'] > $max_rozmiar) {echo "Przekroczenie rozmiaru $max_rozmiar"; }
else
{	
echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';
if (isset($_FILES['plik']['type'])) {echo 'Typ: '.$_FILES['plik']['type'].'<br/>'; }
move_uploaded_file($_FILES['plik']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."./z7/$ciastko/$folder/".$_FILES['plik']['name']);
}
}
else {echo 'Błąd przy przesyłaniu danych!';}
}
}
?>


Pobierz swój plik wpisując adres z listy:
<br />

<form action="<?php $_SERVER['PHP_self'];?>"  method="post">
   <input type="text" name="cos" id="cos">
   <input type="submit" name="pobierz" value="Pobierz">
   </form>
   <br />
   <br />
<form method="POST" action="<?php echo $_SERVER['PHP_self']; ?> ">
<input type="submit" value="Wyloguj" name="wylogowanie"> </a>
</form>
</div>
</body> 
</html>