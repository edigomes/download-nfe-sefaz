# download-nfe-sefaz

API Para download de XML da NF-e direto pelo site da secretária da fazenda.

## Instalação com Composer

Pelo terminal vá até a raiz de seu projeto e lá execute :

```
composer require nfephp-org/nfephp
``` 
Isso fará com que o SEU arquivo composer.json seja acrescido da dependência da API.
A API será baixada e colocada na pasta "vendor" e o arquivo autoload.php sejá atualizado.

##### Exemplos

```php
// Capturando o captcha em formato base64 (png)
$CNPJ = "12345678987654";
$path_cert = "/pasta_do_certificado/";
$senha_cert = "12345678";

$downloadXml = new DownloadNFeSefaz($CNPJ, $path_cert, $senha_cert);
$captcha = $downloadXml->getDownloadXmlCaptcha();

// Exibindo em html
echo '<img src="$captcha">';

// Sabendo o captcha é só fazer o download do XML informando o mesmo e a chave de acesso da NF-e
$captcha = "a7S87hx";
$chave_acesso = "12345678901234567890123456789012345678901234";
$xml = $downloadXml->downloadXmlSefaz($captcha, $chave_acesso);

echo $xml;
