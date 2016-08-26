<?php

require './vendor/autoload.php';

// Iniciando a classe

use DownloadNFeSefaz\DownloadNFeSefaz;

$downloadXml = new DownloadNFeSefaz($CNPJ, $path_cert, $senha_cert);

// Capturando o captcha em formato base64 (png)
$captcha = $downloadXml->getDownloadXmlCaptcha();

// Exibindo em html
echo "<img src=\"$captcha\">";