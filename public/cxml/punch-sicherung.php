<?php
$inputXML = file_get_contents('php://input');
$postedXML = trim($inputXML);
$xml=simplexml_load_string($postedXML) or die("Error: Cannot create object");
$xml_array = json_decode(json_encode((array)$xml), TRUE);

$idendity = $xml_array["Header"]["From"]["Credential"][1]["Identity"];

////////////////////////// cXML Response ////////////////////////////////////

$dom = new DOMDocument('1.0', 'utf-8');
$root = $dom->createElement('Response');
$dom->appendChild($root);
$root->appendChild($firstNode = $dom->createElement("Status"));
$firstNode->setAttribute("code", "200");
$firstNode->setAttribute("text", "success");

$root->appendChild($secondNode = $dom->createElement("PunchOutSetupResponse"));
$secondNode->appendChild($startPage = $dom->createElement("StartPage"));


$startPage->appendChild($dom->createElement("URL",'https://hartmann.bairle.de/login.html'));

/*echo $dom->saveXML();*/

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


$xml->asXML();

echo $dom->saveXML();

?>