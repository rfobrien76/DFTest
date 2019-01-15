<?php
//OK - This is the final working DEV section - cleaned up - 5 Oct 2018 try 2
$test = 1;

$payload = $event['request']['payload'];

if($test){
    
    echo "\n\n\n\n\n";
    echo "AWorkstationAK " . $payload['AWorkstationAK'] . "\n";
    echo "AUserName ". $payload['AUserName'] . "\n"; 
    echo "APassword " . $payload['APassword'] . "\n";
    echo "ovwsessionID11111". $payload['ovwsessionID'] . "\n\n\n\n\n";
}

$soap_options = array(
  
    'location'  => "https://bme-test.biltmore.com:23051/BosServices.dll/soap/IWsAPIUser?ovwsessionid=". $payload['ovwsessionID'],
    'uri'  => "urn:WsAPIUserIntf-IWsAPIUser",
    'trace'       => 1,     // traces let us look at the actual SOAP messages later
    'exceptions'  => 1 
);

if (!class_exists('SoapClient')){
    die ("You haven't installed the PHP-Soap module.");
}

$client = new SoapClient(null,$soap_options);
    try {
        $value = $client->UserLogin(
        new SoapParam($payload['AWorkstationAK'], "AWorkstationAK"),
        new SoapParam($payload["AUserName"], "AUserName"),
        new SoapParam($payload["APassword"], "APassword"));
    }   catch (SOAPFault $f) {
            echo $f->faultstring;// handle the fault here
  
    }
if($test){
    $lastrequest = htmlentities($client->__getLastRequest());
    $lastresponse = htmlentities($client->__getLastResponse());
    $lastrequest_dec = html_entity_decode($client->__getLastRequest());
    $lastresponse_dec = html_entity_decode($client->__getLastResponse());
    echo $lastrequest_dec . "<br><br>";
    echo $lastresponse_dec;
    echo "Error: " . $value->ERROR->CODE. " " . $value->ERROR->TEXT;

    if ($value->ERROR->CODE == "200")
    {
        echo "<br>Sessionid = " . $value->SESSIONID;
    }
}
$event['response'] = json_encode($value);
