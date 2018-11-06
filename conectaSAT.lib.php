<?php

    function open(){
    	$direimg = APROOT."/captcha/".preg_replace("/[^a-zA-Z0-9]/", "", $_SESSION['compania']->get('rfc') )."/".CiaSucursal::value( 'id' )."/";
    	$cookie= $direimg.'cookie.txt';
		
    	$url = 'https://verificacfdi.facturaelectronica.sat.gob.mx/';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);  
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt ($ch, CURLOPT_REFERER, $url);
        $result = curl_exec($ch);  
        curl_close($ch);

        return $result;
    }

    function get_captcha()
    {
        $url    = 'https://verificacfdi.facturaelectronica.sat.gob.mx/';
        $open   = open($url);//
        $doc = new DOMDocument();
		$doc->loadHTML($open);
		$xpath = new DOMXpath($doc);
		$result = $xpath->query( '//*[@id="ctl00_MainContent_ImgCaptcha"]');
		foreach($result as $r){
	    	$dirImg = $r->getAttribute('src'); 
		}
		if($dirImg != '') $direCaptcha = 'https://verificacfdi.facturaelectronica.sat.gob.mx/'.$dirImg;
		if ($direCaptcha == '') $direCaptcha = 'ERROR';
        return $direCaptcha;

    }

    function validarUUID($uuid, $rfcemi, $rfcrecept, $direCaptcha)
    {
        //$capth=htmlspecialchars($_POST['code']);
    	$capth = htmlspecialchars($direCaptcha);

        $UUID=$uuid; 
        $Emisor=$rfcemi; 
        $Receptor = $rfcrecept;
        $url='https://verificacfdi.facturaelectronica.sat.gob.mx/';
        $direimg = APROOT."/captcha/".preg_replace("/[^a-zA-Z0-9]/", "", $_SESSION['compania']->get('rfc') )."/".CiaSucursal::value( 'id' )."/";
    	$cookie= $direimg.'cookie.txt';
        $CaptchaNumbers=$capth;
        //debug($cookie);
        $com=htmlspecialchars("Verificar CFDI");

        $nombreUUID = htmlspecialchars('ctl00$MainContent$TxtUUID');

        $postdata = $nombreUUID.'='.$UUID.'&ctl00$MainContent$TxtRfcEmisor='.$Emisor.'&ctl00$MainContent$TxtRfcReceptor='.$Receptor.'&ctl00$MainContent$TxtCaptchaNumbers='.$CaptchaNumbers.'&ctl00$MainContent$BtnBusqueda='.$com;
        //debug($postdata);
        $ch = curl_init(); 
        curl_setopt ($ch, CURLOPT_URL, $url); 
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6"); 
        curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie); 
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookie);  // <-- add this line
        curl_setopt ($ch, CURLOPT_REFERER, $url); 

        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata); 
        curl_setopt ($ch, CURLOPT_POST, 1); 
        $result = curl_exec ($ch); 

        echo $result;

        //echo '<textarea>'.$result.'<textarea>';  

        //$data = curl_exec($ch);

        //echo $result;

    }