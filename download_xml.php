<?php

// Autoload
require './vendor/autoload.php';

// Namespace
use DownloadNFeSefaz\DownloadNFeSefaz;

// Iniciando a classe
$downloadXml = new DownloadNFeSefaz();

// CNPJ do certificado digital
$CNPJ = '12345678901234';

// Pasta onde se encontram os arquivos .pem
// {CNPJ}_priKEY.pem
// {CNPJ}_certKEY.pem
// {CNPJ}_pubKEY.pem
$path_cert = '\pasta_do_certificado\\';

// Senha do certificado
$senha_cert = '12345678';

// Sabendo o captcha é só fazer o download do XML informando o mesmo e a chave de acesso da NF-e
$captcha = '{captcha}';

// Chave de acesso
$chave_acesso = '12345678901234567890123456789012345678901234';

$xml = $downloadXml->downloadXmlSefaz($captcha, $chave_acesso, $CNPJ, $path_cert, $senha_cert);

echo $xml;