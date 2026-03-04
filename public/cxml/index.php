<?php
require_once("vendor/autoload.php"); 

$requestXml = file_get_contents('php://input');

// Parse request XML (PunchOutSetupRequest)
$xmlParser = new CXmlParser();
$cXmlRequest = $xmlParser->parse($requestXml);

/** @var PunchOutSetupRequest $setupRequest */
$setupRequest = $cXmlRequest->getRequests()[0];

// Check request
if (!$setupRequest || !$setupRequest instanceof PunchOutSetupRequest) {
    throw new Exception('Invalid request');
}

// Get credentials
$user = $cXmlRequest->getHeader()->getSenderIdentity();
$password = $cXmlRequest->getHeader()->getSenderSharedSecret();

// Get punchout data
$buyerCookie = $setupRequest->getBuyerCookie();
$postUrl = $setupRequest->getBrowserFormPostUrl();

// Create startPageUrl (store submitted data in your database and generate a login URL with a hash)
$startPageUrl = $this->generateStartPageUrl($user, $password, $buyerCookie, $postUrl);

// Create cXML envelope and status
$cXml = $cxml = new CXml();
$cxml->setPayloadId(time() . '@' . $this->app->getCurrentRequest()->getHost());
$cXml->addResponse(new Status());

// Create PunchOutSetupResponse
$response = new PunchOutSetupResponse();
$response->setStartPageUrl($startPageUrl);
$cXml->addResponse($response);

// Return response XML
return $cXml->render();