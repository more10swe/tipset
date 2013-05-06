<?php

	include '../secretstuff.php';

	$timeout = 20;

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

	try {
	    $response = curl_exec($curl);
	    curl_close($curl);

	    // success! Let's parse it and perform whatever action necessary here.
	    if ($response !== false) {
	        /** @var $xml SimpleXMLElement */
	        $xml = simplexml_load_string($response);
	        $xml->saveXML("datan.xml");
	    } else {
	        // Warn the user here
	    }
	} catch (Exception $ex) {
	    // Let user's know that there was a problem
	    curl_close($curl);
	}


?>
