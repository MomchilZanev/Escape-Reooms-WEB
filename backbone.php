<?php

session_start();

require 'vendor/autoload.php'; // Include the Composer autoloader

use Stichoza\GoogleTranslate\GoogleTranslate;

function translateString($string, $languageFrom, $languageTo) {
    $translate = new GoogleTranslate($languageFrom);
    $translatedText = $translate->setSource($languageFrom)->setTarget($languageTo)->translate($string);

    
    return $translatedText;   
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8" />
    <title>Backbone</title>
    <script defer src="scripts/menuScript.js"></script>
    <link rel="stylesheet" href="css/backboneStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>

<body>
  
  <header>
    <div class="box">
      <div class="btnMenu">
        <span class="fas fa-bars"></span>
      </div>
    </div>

    <div class="box" id="headerCenter">
      <h1 id="caption"><?php echo translateString("Escaperoom demo", "en", $_POST['lang']) ?></h1>
    </div>

    <div class="box" id="headerRight">
      <button type="button" id="LanguageIconBtn"><?php echo strtoupper($_POST['lang']) ?></button>
    </div>
  
  </header>

  <nav class="sidebar">
    <ul>
      <li class="active"><a href="#url..."> <?php echo translateString("Homepage", "en", $_POST['lang']) ?> </a></li>
      <li><a href="#url..."> <?php echo translateString("Show riddles", "en", $_POST['lang']) ?></a></li>
      <li><a href="#url..."> <?php echo translateString("Import a room", "en", $_POST['lang']) ?> </a></li>
      <li><a href="#url..."> <?php echo translateString("Export rooms", "en", $_POST['lang']) ?></a></li>
      <li><a href="#url..."> <?php echo translateString("Documentation", "en", $_POST['lang']) ?> </a></li>
    </ul>
  </nav>
</body>
</html>