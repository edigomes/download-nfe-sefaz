<?php

// Autoload
require './vendor/autoload.php';

// Namespace
use DownloadNFeSefaz\DownloadNFeSefaz;

// Iniciando a classe
$downloadXml = new DownloadNFeSefaz();

// Capturando o captcha em formato base64 (png)
$captcha = $downloadXml->getDownloadXmlCaptcha();

// Exibindo em html
echo "<img src=\"$captcha\">";