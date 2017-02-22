# download-nfe-sefaz

API Para download de XML da NF-e direto pelo site da secretária da fazenda.

## Instalação com Composer

Pelo terminal vá até a raiz de seu projeto e lá execute :

```
composer require edigomes/download-nfe-sefaz
``` 
Isso fará com que o SEU arquivo composer.json seja acrescido da dependência da API.
A API será baixada e colocada na pasta "vendor" e o arquivo autoload.php sejá atualizado.

## Exemplos

##### Requisitando o captcha (captcha.php)

```php
// Autoload
require './vendor/autoload.php';

// Iniciando a classe

use DownloadNFeSefaz\DownloadNFeSefaz;

$downloadXml = new DownloadNFeSefaz();

// Capturando o captcha em formato base64 (png)
$captcha = $downloadXml->getDownloadXmlCaptcha();

// Exibindo em html
echo "<img src=\"$captcha\">";

```

Com o captcha em mãos vamos fazer o download do XML (quem sabe consseguimos quebrar esse captcha depois)

##### Fazendo o download do XML (download_xml.php)

```php
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
$captcha = 'digiteaquiocaptcha';

// Chave de acesso
$chave_acesso = '12345678901234567890123456789012345678901234';

$xml = $downloadXml->downloadXmlSefaz($captcha, $chave_acesso, $CNPJ, $path_cert, $senha_cert);

echo $xml;
```

Qualquer dúvida pergunte [aqui](https://groups.google.com/forum/#!topic/nfephp/H7UdfhnbKXE).

Edimário Gomes - Aconos Tecnologia: 
edi.gomes00@gmail.com,
edi.gomes@aconos.com.br