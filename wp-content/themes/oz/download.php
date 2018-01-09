<?php
  $filename = '';
  $special = false;

  if($_POST['download_code']=='zuckerwatte') {
    $filename = 'wunstmukke-001.mp3';
  }
  elseif($_POST['download_code']=='wirsind') {
    $filename = 'wunstmukke-die-erste.mp3';
  }
  elseif($_POST['download_code']=='CAPTAIN PLANET') {
    $filename = 'Benno-Blome-Expedition-Eramina-320.mp3';
  }
  elseif($_POST['download_code']=='vorspielen') {
    $filename = 'wunstmukke-die-zweite.mp3';
  }
  elseif($_POST['download_code']=='fürMutti') {
    $filename = 'wunstmukke-002.mp3';
  }
  elseif($_POST['download_code']=='verschiebung') {
    $filename = 'wunstmukke-003.mp3';
  }
  elseif($_POST['download_code']=='reineke fuchs') {
    $filename = 'wunstmukke-004.mp3';
  }
  elseif($_POST['download_code']=='darwin') {
    $filename = 'wunstmukke-005.mp3';
  }
  elseif($_POST['download_code']=='lachen') {
    $filename = 'wunstmukke-die-dritte.mp3';
  }
  elseif($_POST['download_code']=='phantasie') {
    $filename = 'wunstmukke-die-fuenfte.mp3';
  }
  elseif($_POST['download_code']=='erich fromm') {
    $filename = 'wunstmukke-die-fuenfte.mp3';
  }
  elseif($_POST['download_code']=='SPIELEN') {
    $filename = 'wunstmukke-die-sechste.mp3';
  }
  elseif($_POST['download_code']=='SEIN') {
    $filename = 'wunstmukke-die-siebte.mp3';
  }
  elseif($_POST['download_code']=='meintag') {
    $filename = 'marcel-und-herr-wiesner_das-beste-an-meinem-tag.mp3';
  }
  elseif($_POST['download_code']=='HEINZ') {
    $filename = 'this-is-heinz_promo-mix-2013.mp3';
  }
  elseif($_POST['download_code']=='katergrinse') {
    $filename = 'Miau.zip';
    $special  = true;
  }
  elseif($_POST['download_code']=='Gandalf') {
    $filename = 'wunstmukke-die-achte.mp3';
  }
  elseif($_POST['download_code']=='Glücksprinzip') {
    $filename = 'wunstmukke-die-neunte.mp3';
  }
  elseif($_POST['download_code']=='genese') {
    $filename = 'wunstmukke-die-elfte.mp3';
  }
  elseif($_POST['download_code']=='balduin') {
    $filename = 'wunstmukke-die-zehnte.mp3';
  }
  elseif($_POST['download_code']=='Glockenspiel') {
    $filename = 'empro_glockenspiel.mp3';
  }
  elseif($_POST['download_code']=='ROSA VOGEL') {
    $filename = 'wunstmukke-006.mp3';
  }
  elseif($_POST['download_code']=='conzuela') {
    $filename = 'wunstmukke-007.mp3';
  }
  elseif($_POST['download_code']=='two things') {
    $filename = 'wunstmukke-die-zwoelfte.mp3';
  }
  elseif($_POST['download_code']=='Gilbert Martini') {
    $filename = 'wunstmukke-013.m4a';
  }
  elseif($_POST['download_code']=='Hr.Klotz') {
    $filename = 'wunstmukke-014.mp3';
  }
  elseif($_POST['download_code']=='zocco schwolf') {
    $filename = 'wunstmukke-015.mp3';
  }
  elseif($_POST['download_code']=='Etzo') {
    $filename = 'wunstmukke-016.mp3';
  }
  elseif($_POST['download_code']=='NICO') {
    $filename = 'wunstmukke-017.mp3';
  }
  //elseif($_POST['download_code']=='') {
  //  $filename = '';
  //}
  else {
    die('ERROR - Access denied!');
  }

  $url = '../../uploads/wunstmukke/' . $filename;
  $file = $_GET[$url];
  header('Content-Description: File Transfer');

  if($special) {
    header('Content-Type: application/zip');
  } else {
    header('Content-Type: audio/mpeg');
  }

  header('Content-Disposition: attachment; filename="' . $filename . '"');
  header('Content-Transfer-Encoding: binary');

  readfile($url);
  exit;
?>
