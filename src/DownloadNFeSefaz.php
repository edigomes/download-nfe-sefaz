<?php

namespace DownloadNFeSefaz;

use DOMDocument;
use Exception;

/*
 * API Para download de XML da NF-e direto pelo site da secretária da fazenda
 */

/**
 * Description of DownloadNFeSefaz
 *
 * @author Edimário Gomes <edi.gomes00@gmail.com>
 * @license GPL
 */
class DownloadNFeSefaz {

    /**
     * CNPJ da empresa emitente 
     * @var String
     */
    private $CNPJ;

    /**
     * Pasta onde se encontram os arquivos .pem
     * {CNPJ}_priKEY.pem
     * {CNPJ}_certKEY.pem
     * {CNPJ}_pubKEY.pem
     * do certificado A1 (pasta certs do nfe php)
     * ($this->aConfig['pathCertsFiles'])
     * @var type String
     */
    private $pathCertsFiles;

    /**
     * Senha do certificado
     * @var type 
     */
    private $certPass;

    public function __construct() {
        error_reporting(E_ERROR | ~E_WARNING);
    }

    /**
     * Faz o download da NF-e no site da sefaz usando o certificado digital do cliente
     * @param type $txtCaptcha Captcha fornecedo por getDownloadXMLCaptcha
     * @param type $chNFe Chave de acesso da NF-e
     * @return String XML da NF-e
     */
    public function downloadXmlSefaz($txtCaptcha, $chNFe, $CNPJ, $pathCertsFiles, $certPass) {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);

        // TODO: Validar CNPJ
        $this->CNPJ = $CNPJ;
        // TODO: Validar se existe a pasta e os arquivos .pem
        $this->pathCertsFiles = $pathCertsFiles;
        // TODO: Validar senha do certificado
        $this->certPass = $certPass;

        // TODO: validar chNFe 44 digitos

        /* Verificando se a session já não foi aberta */
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        // URL onde a sefaz fornece o botão de download
        $url = "https://www.nfe.fazenda.gov.br/portal/consulta.aspx?tipoConsulta=completa&tipoConteudo=XbSeqxE8pl8%3d";
        // Arquivo de coockie para armazenar a session
        $cookie = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cookies1.txt';
        // Simula um browser pelo curl
        $useragent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.99 Safari/535.1';

        /* Start Login process */
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        //$verbose = fopen('log.txt', 'w+');
        //curl_setopt($ch, CURLOPT_STDERR, $verbose);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);

        // Collecting all POST fields
        $postfields = array();
        $postfields['__EVENTTARGET'] = "";
        $postfields['__EVENTARGUMENT'] = "";
        $postfields['__VIEWSTATE'] = $_SESSION['viewstate'];
        $postfields['__VIEWSTATEGENERATOR'] = $_SESSION['stategen'];
        $postfields['__EVENTVALIDATION'] = $_SESSION['eventValidation'];

        $postfields['ctl00$txtPalavraChave'] = "";

        $postfields['ctl00$ContentPlaceHolder1$txtChaveAcessoCompleta'] = $chNFe;
        $postfields['ctl00$ContentPlaceHolder1$txtCaptcha'] = $txtCaptcha;
        $postfields['ctl00$ContentPlaceHolder1$btnConsultar'] = 'Continuar';
        $postfields['ctl00$ContentPlaceHolder1$token'] = $_SESSION['token'];
        $postfields['ctl00$ContentPlaceHolder1$captchaSom'] = $_SESSION['captchaSom'];
        $postfields['hiddenInputToUpdateATBuffer_CommonToolkitScripts'] = '1';

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        // Result
        $html = curl_exec($ch);

        preg_match('~Dados da NF-e~', $html, $tagTeste);

        if (isset($tagTeste[0])) {
            $tagDownload = $tagTeste[0];
        } else {
            throw new Exception('Sessão expirada ou captcha inválido, gere um novo captcha e tente novamente.');
        }

        $document = new DOMDocument();
        $document->loadHTML($html);

        $viewstate = $document->getElementById('__VIEWSTATE')->getAttribute('value');
        $stategen = $document->getElementById('__VIEWSTATEGENERATOR')->getAttribute('value');
        $eventValidation = $document->getElementById('__EVENTVALIDATION')->getAttribute('value');

        curl_close($ch);
        //fclose($verbose);

        // Parãmetro teste para saber se a página veio corretamente
        if ($tagDownload == "Dados da NF-e") {

            // URL onde a sefaz fornece o download do xml
            $url_download = "https://www.nfe.fazenda.gov.br/portal/consultaCompleta.aspx?tipoConteudo=XbSeqxE8pl8=";

            // Verifica se o certificado existe na pasta
            if (!file_exists($this->pathCertsFiles . $this->CNPJ . '_priKEY.pem') ||
                    !file_exists($this->pathCertsFiles . $this->CNPJ . '_priKEY.pem') ||
                    !file_exists($this->pathCertsFiles . $this->CNPJ . '_priKEY.pem')) {
                throw new Exception('Certificado digital não encontrado na pasta: ' . $this->pathCertsFiles . '!');
            }

            /** Download do xml * */
            $ch_download = curl_init($url_download);

            curl_setopt($ch_download, CURLOPT_COOKIEJAR, $cookie);
            curl_setopt($ch_download, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch_download, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch_download, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch_download, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch_download, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch_download, CURLOPT_USERAGENT, $useragent);
            //$verbose = fopen('log.txt', 'w+');
            //curl_setopt($ch_download, CURLOPT_STDERR, $verbose);
            //curl_setopt($ch_download, CURLOPT_VERBOSE, true);

            // this with CURLOPT_SSLKEYPASSWD 
            curl_setopt($ch_download, CURLOPT_SSLKEY, $this->pathCertsFiles . $this->CNPJ . '_priKEY.pem');
            // The --cacert option
            curl_setopt($ch_download, CURLOPT_CAINFO, $this->pathCertsFiles . $this->CNPJ . '_certKEY.pem');
            // The --cert option
            curl_setopt($ch_download, CURLOPT_SSLCERT, $this->pathCertsFiles . $this->CNPJ . '_pubKEY.pem');
            // Cert pass
            curl_setopt($ch_download, CURLOPT_SSLCERTPASSWD, $this->certPass);

            // Collecting all POST fields
            $postfields_download = array();
            $postfields_download['__EVENTTARGET'] = "";
            $postfields_download['__EVENTARGUMENT'] = "";
            $postfields_download['__VIEWSTATE'] = $viewstate;
            $postfields_download['__VIEWSTATEGENERATOR'] = $stategen;
            $postfields_download['__EVENTVALIDATION'] = $eventValidation;
            $postfields_download['ctl00$txtPalavraChave'] = '';
            $postfields_download['ctl00$ContentPlaceHolder1$btnDownload'] = 'Download do documento*';
            $postfields_download['ctl00$ContentPlaceHolder1$abaSelecionada'] = '';
            $postfields_download['hiddenInputToUpdateATBuffer_CommonToolkitScripts'] = 1;

            curl_setopt($ch_download, CURLOPT_POST, 1);
            curl_setopt($ch_download, CURLOPT_POSTFIELDS, $postfields_download);

            $response_xml = curl_exec($ch_download);

            // Verifica xml
            $doc = simplexml_load_string($response_xml);

            if ($doc) {
                curl_close($ch_download);
                //fclose($verbose);
                return $response_xml;
            } else {
                $document = new DOMDocument();
                $document->loadHTML($response_xml);
                $result = $document->getElementById('ctl00_ContentPlaceHolder1_lblResultadoConsulta')->nodeValue;
                throw new Exception($result);
            }

        } else {
            throw new Exception('Não foi possível fazer o download do XML, verifique o debug');
        }
    }

    /**
     * Retorna o captcha da sefaz para download do XML
     * no formato base64 (png)
     * @return String base64 png
     */
    public function getDownloadXmlCaptcha() {

        /* Verificando se a session já não foi aberta */
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        // Passo 1
        $url = "https://www.nfe.fazenda.gov.br/portal/consulta.aspx?tipoConsulta=completa&tipoConteudo=XbSeqxE8pl8=";
        $cookie = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cookies1.txt';
        $useragent = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.2 (KHTML, like Gecko) Chrome/5.0.342.3 Safari/533.2';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        //$verbose = fopen('log.txt', 'w+');
        //curl_setopt($ch, CURLOPT_STDERR, $verbose);
        //curl_setopt($ch, CURLOPT_VERBOSE, TRUE);

        $html = curl_exec($ch);

        $document = new DOMDocument();
        $document->loadHTML($html);

        $viewstate = $document->getElementById('__VIEWSTATE')->getAttribute('value');
        $stategen = $document->getElementById('__VIEWSTATEGENERATOR')->getAttribute('value');
        $eventValidation = $document->getElementById('__EVENTVALIDATION')->getAttribute('value');
        $token = $document->getElementById('ctl00_ContentPlaceHolder1_token')->getAttribute('value');
        $captchaSom = $document->getElementById('ctl00_ContentPlaceHolder1_captchaSom')->getAttribute('value');

        preg_match('~<img id=\"ctl00_ContentPlaceHolder1_imgCaptcha\" src=\"(.*)\" ~', $html, $_captcha);

        $_SESSION['viewstate'] = $viewstate;
        $_SESSION['stategen'] = $stategen;
        $_SESSION['eventValidation'] = $eventValidation;
        $_SESSION['token'] = $token;
        $_SESSION['captchaSom'] = $captchaSom;

        $captcha = $_captcha[1];

        curl_close($ch);
        //fclose($verbose);

        return $captcha;
    }

}
