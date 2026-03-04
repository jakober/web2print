<?php
session_start();
$raw = file_get_contents("php://input");

$response = $_POST['cxml-urlencoded'];
$response = urldecode($response);

$response = '<?xml version="1.0"?>
<!DOCTYPE cXML SYSTEM "http://xml.cxml.org/schemas/cXML/1.2.044/cXML.dtd">
<cXML payloadID="1644956851513.892339465@app299.eu1.ariba.com" timestamp="2022-02-15T12:27:31-08:00" xml:lang="en">
	<Header>
		<From>
			<Credential domain="SystemID">
				<Identity>CHILD1</Identity>
			</Credential>
			<Credential domain="NetworkId">
				<Identity>AN01441473104-T</Identity>
			</Credential>
			<Credential domain="EndPointID">
				<Identity>Ariba</Identity>
			</Credential>
		</From>
		<To>
			<Credential domain="buyersystemid">
				<Identity>0000001524</Identity>
			</Credential>

			<Credential domain="internalsupplierid">
				<Identity>0000001524</Identity>
			</Credential>

			<Credential domain="networkid">
				<Identity>an01685232445-t</Identity>
			</Credential>

			<Credential domain="transactionnetworkid">
				<Identity>an01685232445-t</Identity>
			</Credential>
		</To>
		<Sender>
			<Credential domain="SystemID">
				<Identity>CHILD1</Identity>
				<SharedSecret>bairledruck89</SharedSecret>
			</Credential>
			<Credential domain="NetworkId">
				<Identity>AN01441473104-T</Identity>
				<SharedSecret>bairledruck89</SharedSecret>
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
				<URL>https://hartmann.bairle.de/cxml/punch.php</URL>
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
'
;

$dom = new DOMDocument();
$dom->loadXML($response);
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$cxml = $dom->saveXML();
$cxml = htmlentities($cxml);


?><!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>Punch Out!</title>
  <script type="text/javascript" src="sh_main.min.js"></script>
  <script type="text/javascript" src="sh_xml.min.js"></script>
  <link type="text/css" rel="stylesheet" href="sh_emacs.min.css" />
 </head>

 <body onload="sh_highlightDocument();">
  <a href="./">&laquo; Punch again</a>
  <hr />
  <h2>PunchOut Callback</h2>
  <pre class="sh_xml"><?= $cxml ?></pre>

  <hr />
  <h2>POST    </h2> <pre><? print_r($_POST);    ?></pre>
  <h2>GET     </h2> <pre><? print_r($_GET);     ?></pre>
  <h2>SESSION </h2> <pre><? print_r($_SESSION); ?></pre>
  <h2>COOKIE  </h2> <pre><? print_r($_COOKIE);  ?></pre>
  <h2>SERVER  </h2> <pre><? print_r($_SERVER);  ?></pre>
  <h2>RAW     </h2> <pre><? print_r($raw);  ?></pre>
 </body>
</html>
