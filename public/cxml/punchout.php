<?php
$payload = uniqid();
$date = date("Y-m-d\TH:i:sP");
$hook = "hook.php";
$inputXML = NULL;
$replaceHook = true;

if (!empty($_POST['inputXML'])) {
    $replaceHook = isset($_POST['replaceHook']);
    $inputXML = $_POST['inputXML'];
} else {
    $replaceHook = true;
    $username = (isset($_POST['USERNAME']) ? $_POST['USERNAME'] : null);
    $password = (isset($_POST['PASSWORD']) ? $_POST['PASSWORD'] : null);
    $setupURL = (isset($_POST['PUNCHOUT_LOGIN_URL']) ? $_POST['PUNCHOUT_LOGIN_URL'] : null);
    $inputXML = <<<EOT
<?xml version="1.0"?>
<!DOCTYPE cXML SYSTEM "http://xml.cxml.org/schemas/cXML/1.2.044/cXML.dtd">
<cXML payloadID="1644956851513.892339465@app299.eu1.ariba.com" timestamp="2022-02-15T12:27:31-08:00" xml:lang="en">
    <Header>
        <!-- class: ariba.encoder.xml.AXComponent -->

<From>
<!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="SystemID">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>CHILD1</Identity>
</Credential><!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="NetworkId">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>AN01441473104-T</Identity>
</Credential><!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="EndPointID">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>Ariba</Identity>
</Credential>
</From>
<To>
<!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="buyersystemid">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>0000001524</Identity>
</Credential><!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="internalsupplierid">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>0000001524</Identity>
</Credential><!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="networkid">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>an01685232445-t</Identity>
</Credential><!-- class: ariba.encoder.xml.AXComponent -->
<Credential domain="transactionnetworkid">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>an01685232445-t</Identity>
</Credential>
</To>
<Sender>
<Credential domain="SystemID">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>CHILD1</Identity>
<SharedSecret>***shared secret removed***</SharedSecret>
</Credential>
<Credential domain="NetworkId">
 <!-- class: ariba.encoder.xml.AXComponent -->
<Identity>AN01441473104-T</Identity>
<SharedSecret>***shared secret removed***</SharedSecret>
</Credential>
<UserAgent>Buyer 14s2</UserAgent>

</Sender>
    </Header>
    <Request>
        <PunchOutSetupRequest operation="create">
            <BuyerCookie>1TEZ7HFKTBP2M</BuyerCookie>
            <Extrinsic name="UniqueName">HG0031048</Extrinsic>
            <Extrinsic name="UserEmail">Ivana.Mackova@hartmann.info</Extrinsic>
            <Extrinsic name="CompanyCode">0126</Extrinsic>
            <BrowserFormPost>
                <URL>https://s1-eu.ariba.com/Buyer/punchout?client=HTML.E7956A53DA0600904DC5B8B02445CF2F.Node14app299eu1&amp;responseid=a&amp;locale=en</URL>
            </BrowserFormPost>
            <SupplierSetup>
                <URL>https://hartmann.bairle.de/punchout.php</URL>
            </SupplierSetup>
            <ShipTo>
                    <Address addressID="0100">
                        <Name xml:lang="en">0100</Name>
                        <PostalAddress>
                            <DeliverTo>Ivana Mackova</DeliverTo>
                            <Street>Kieler Straße 371-377</Street>
                            <City>Hamburg</City>
                            <PostalCode>22525</PostalCode>
                            <Country isoCountryCode="DE">Germany</Country>
                        </PostalAddress>
                    </Address>
            </ShipTo>
            <SelectedItem>
                <ItemID>
                    <SupplierPartID>AAA</SupplierPartID>
                    <SupplierPartAuxiliaryID></SupplierPartAuxiliaryID>
                </ItemID>
            </SelectedItem>
        </PunchOutSetupRequest>
    </Request>
</cXML>
EOT;
}
?><?php //Trick to fool syntax higlighting

$postedXML = trim($inputXML);
try {
    $inputDoc = new SimpleXMLElement($postedXML);
    $setupURL = $inputDoc->Request->PunchOutSetupRequest->SupplierSetup->URL[0];
    if ($replaceHook) {
        $inputDoc->Request->PunchOutSetupRequest->BrowserFormPost->URL[0] = $hook;
        $postedXML = $inputDoc->asXML();
    }

    $ch = curl_init($setupURL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postedXML);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curlOutput = curl_exec($ch);
    $result = curl_getinfo($ch);
    curl_close($ch);

    $ok = false;
    $url = "";
    if ($result["http_code"] >= 200 && $result["http_code"] <= 299) {
        $ok = true;
        $outputDoc = new SimpleXMLElement($curlOutput);
        $url = $outputDoc->Response[0]->PunchOutSetupResponse[0]->StartPage[0]->URL[0];
    } else {
        throw new Exception($curlOutput, $result["http_code"]);
    }

    $dom = new DOMDocument();
    $dom->loadXML($curlOutput);
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $outputXML = $dom->saveXML();
    unset($dom);

    $dom = new DOMDocument();
    $dom->loadXML($postedXML);
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->normalizeDocument();
    $postedXML = $dom->saveXML();
    unset($dom);

} catch (Exception $e) {
    $dom = new DOMDocument();
    $dom->appendChild($dom->createElement("message", $e->getMessage()));
    $dom->appendChild($dom->createElement("code",    $e->getCode()));
    //$dom->appendChild($dom->createElement("file",    $e->getFile()));
    //$dom->appendChild($dom->createElement("line",    $e->getLine()));
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->normalizeDocument();
    $outputXML = $dom->saveXML();
}


$outputXML = htmlentities($outputXML);
$inputXML = htmlentities($inputXML);
$postedXML = htmlentities($postedXML);

?><!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Punch Out Test</title>
  <script type="text/javascript" src="sh_main.min.js"></script>
  <script type="text/javascript" src="sh_xml.min.js"></script>
  <link type="text/css" rel="stylesheet" href="sh_emacs.min.css" />
 </head>

 <body onload="sh_highlightDocument();">
  <h2><?= ($ok ? "Success!" : "Failure!") ?></h2>
  <? if ($ok): ?>
   <p><a target="_top" href="<?= htmlentities($url) ?>">Proceed to <?= htmlentities($url) ?></a></p>
  <? endif ?>

  <hr />

  <h2>XML Response:</h2>
  <pre class="sh_xml"><?= $outputXML ?></pre>

  <h2>XML Post:</h2>
  <pre class="sh_xml"><?= $inputXML ?></pre>

  <h2>XML Input:</h2>
  <pre class="sh_xml"><?= $inputXML ?></pre>

  <h2>cURL:</h2>
  <pre><?= htmlentities(print_r($result, true)) ?></pre>

  <h2>POST:</h2>
  <pre><?= htmlentities(print_r($_POST, true)) ?></pre>


 </body>
</html><