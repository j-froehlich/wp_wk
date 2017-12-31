<?php
if($_POST['download_code']=='zuckerwatte')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-001.mp3");
	$handle = fopen('../../uploads/wunstmukke/wunstmukke-001.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='wirsind')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-erste.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-erste.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='CAPTAIN PLANET')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=Benno-Blome-Expedition-Eramina-320.mp3");
	$handle = fopen('wunstmukke/Benno-Blome-Expedition-Eramina-320.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='vorspielen')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-zweite.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-zweite.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);

}
elseif($_POST['download_code']=='fürMutti')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-002.mp3");
	$handle = fopen('wunstmukke/wunstmukke-002.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='verschiebung')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-003.mp3");
	$handle = fopen('wunstmukke/wunstmukke-003.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='reineke fuchs')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-004.mp3");
	$handle = fopen('wunstmukke/wunstmukke-004.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='darwin')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-005.mp3");
	$handle = fopen('wunstmukke/wunstmukke-005.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='lachen')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-dritte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-dritte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='phantasie')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-vierte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-vierte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='erich fromm')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-fuenfte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-fuenfte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='SPIELEN')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-sechste.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-sechste.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='SEIN')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=wunstmukke-die-siebte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-siebte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='meintag')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; filename=marcel-und-herr-wiesner_das-beste-an-meinem-tag.mp3");
	$handle = fopen('wunstmukke/marcel-und-herr-wiesner_das-beste-an-meinem-tag.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='HEINZ')
{
	header("Content-type: audio/mpeg");
	header("Content-Disposition: attachment; this-is-heinz_promo-mix-2013.mp3");
	$handle = fopen('wunstmukke/this-is-heinz_promo-mix-2013.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='katergrinse')
{
	header("Content-type: application/zip");
	header("Content-Disposition: attachment; filename='Miau.zip'");
	$handle = fopen('wunstmukke/Miau.zip', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Gandalf')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-achte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-achte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Glücksprinzip')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-neunte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-neunte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='genese')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-elfte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-elfte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='balduin')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-zehnte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-zehnte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Glockenspiel')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=empro_glockenspiel.mp3");
	$handle = fopen('wunstmukke/empro_glockenspiel.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='ROSA VOGEL')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-006.mp3");
	$handle = fopen('wunstmukke/wunstmukke-006.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='conzuela')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-007.mp3");
	$handle = fopen('wunstmukke/wunstmukke-007.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='two things')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-die-zwoelfte.mp3");
	$handle = fopen('wunstmukke/wunstmukke-die-zwoelfte.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Gilbert Martini') // todo: exchange file -> mp3
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-013.m4a");
	$handle = fopen('wunstmukke/wunstmukke-013.m4a', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Hr.Klotz')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wwunstmukke-014.mp3");
	$handle = fopen('wunstmukke/wunstmukke-014.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='zocco schwolf')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-015.mp3");
	$handle = fopen('wunstmukke/wunstmukke-015.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='Etzo')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-016.mp3");
	$handle = fopen('wunstmukke/wunstmukke-016.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
elseif($_POST['download_code']=='NICO')
{
header("Content-type: audio/mpeg");
header("Content-Disposition: attachment; filename=wunstmukke-017.mp3");
	$handle = fopen('wunstmukke/wunstmukke-017.mp3', 'rb');
	$buffer = '';
	while (!feof($handle)) {
		$buffer = fread($handle, 4096);
		echo $buffer;
		ob_flush();
		flush();
	}
	fclose($handle);
}
else
{
	die('ERROR - Access denied!');
}
?>
