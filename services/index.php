<?php


$openamURL = "https://openam.samples.opencsi.com/openam/";
$openamIdpId = "/Customers/idp";
$openamSpId = "/Customers/sp";
$serviceURL = "https://sp.samples.opencsi.com/simplesaml/module.php/core/authenticate.php?as=sample-service";
$adfsEntityID = "http://idp.samples.opencsi.com/adfs/services/trust";
$cookieName = "iPlanetDirectoryPro";

function validateOpenamtoken($token) {
	global $openamURL;
	$request = new HttpRequest($openamURL . "identity/isTokenValid",  HttpRequest::METH_GET);
	$request->addQueryData(array('tokenid' => $token));
	try {
		$request->send();
		if ($request->getResponseCode() == 200) {
			if(trim($request->getResponseBody()) == "boolean=true") {
				return true;
			}
		}
		
	} catch (Exception $ex) {
		return false;
	}
	return false;
}

if (!empty($_COOKIE[$cookieName]) && validateOpenamtoken($_COOKIE[$cookieName])) {
	header('Location: ' . $openamURL . 'SSORedirect/metaAlias' . $openamIdpId . '?' . $_SERVER['QUERY_STRING']);
} else {
	print "<html><body>";
	print "<a href='" . $openamURL . 'SSORedirect/metaAlias' . $openamIdpId . "?" . $_SERVER['QUERY_STRING'] . "'>S'authentifier avec login/mot de passe du SSO central</a>";
	print "<br/>";
	print "<a href='" . $openamURL . "saml2/jsp/spSSOInit.jsp?metaAlias=" . $openamSpId . "&idpEntityID=" . $adfsEntityID . "&RelayState=" . $serviceURL . "'>S'authentifier avec ADFS</a>";
	print "</body></html>";
}
?>
