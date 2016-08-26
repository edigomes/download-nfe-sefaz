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

##### Requisitando o captcha

```php
// Iniciando a classe

use DownloadNFeSefaz\DownloadNFeSefaz;

// CNPJ do certificado digital
$CNPJ = '12345678987654';

// Pasta onde se encontram os arquivos .pem
// {CNPJ}_priKEY.pem
// {CNPJ}_certKEY.pem
// {CNPJ}_pubKEY.pem
$path_cert = '/pasta_do_certificado/';

// Senha do certificado
$senha_cert = '12345678';

$downloadXml = new DownloadNFeSefaz($CNPJ, $path_cert, $senha_cert);

// Capturando o captcha em formato base64 (png)
$captcha = $downloadXml->getDownloadXmlCaptcha();

// Exibindo em html
echo "<img src=\"$captcha\">";

```

Com o captcha em mãos vamos fazer o download do XML (quem sabe consseguimos quebrar esse captcha depois)

##### Fazendo o download do XML

```php
// Iniciando a classe

use DownloadNFeSefaz\DownloadNFeSefaz;

// CNPJ do certificado digital
$CNPJ = '12345678987654';

// Pasta onde se encontram os arquivos .pem
// {CNPJ}_priKEY.pem
// {CNPJ}_certKEY.pem
// {CNPJ}_pubKEY.pem
$path_cert = '/pasta_do_certificado/';

// Senha do certificado
$senha_cert = '12345678';

$downloadXml = new DownloadNFeSefaz($CNPJ, $path_cert, $senha_cert);

// Sabendo o captcha é só fazer o download do XML informando o mesmo e a chave de acesso da NF-e
$captcha = '{captcha_da_imagem}';
$chave_acesso = '{chave_de_acesso_com_44_digitos}';
$xml = $downloadXml->downloadXmlSefaz($captcha, $chave_acesso);

echo $xml;
```

Qualquer dúvida pergunte [aqui](https://groups.google.com/forum/#!topic/nfephp/H7UdfhnbKXE).

Edimário Gomes - Aconos Tecnologia: 
edi.gomes00@gmail.com,
edi.gomes@aconos.com.br