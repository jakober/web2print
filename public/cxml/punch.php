<?php
/////////////////////// cXML Response neu ///////////////////////
$time = date('Y-m-d')."T".date('H:i:s')."-07:00";
$xmlData = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE cXML SYSTEM "http://xml.cXML.org/schemas/cXML/1.2.040/cXML.dtd">
<cXML payloadID="" timestamp="" />
XML;

$xml = new \SimpleXMLElement($xmlData);

$xml->attributes()->payloadID = "958074700772@www.workchairs.com";
$xml->attributes()->timestamp = $time;

$node1 = $xml->addChild('Response');

$node2 = $node1->addChild('Status');
$node2->addAttribute('code','200');
$node2->addAttribute('text','success');

$node3 = $node1->addChild('PunchOutSetupResponse');
$node3->addChild('StartPage')->addChild('URL', 'https://hartmann.bairle.de/login.html?key=$2a$12$v341CJ0qiVK4BY7');

echo $xml->saveXml();
/*exit;


$xmlData = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE cXML SYSTEM "http://xml.cXML.org/schemas/cXML/1.2.040/cXML.dtd">
<cXML payloadID="" timestamp="" />
XML;

$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom_xml = dom_import_simplexml($xml);
$dom_xml = $dom->importNode($dom_xml, true);
$dom_xml = $dom->appendChild($dom_xml);


$result = $dom->saveXML();

echo $result;*/


?>