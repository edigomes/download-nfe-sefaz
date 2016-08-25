<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DownloadNFeSefaz;

use DownloadNFeSefaz\DownloadNFeSefaz;

/**
 * Description of Test
 *
 * @author Edi
 */
class Test {
    public function run() {
        $downloadXml = new DownloadNFeSefaz("", "", "");
        $captcha = $downloadXml->getDownloadXmlCaptcha();
    }
}
