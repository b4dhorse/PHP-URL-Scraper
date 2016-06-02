<?php
/*
Use this to find URLs in content that are contained an href, e.g. href="domain.com"
*/

//URL to scrape
$url = "http://www.vexingmedia.com";

//get protocol
$protocol = substr($url,0,strpos($url,":"));

//get main domain
$domain = str_replace(" ","",parse_url($url,PHP_URL_HOST));

//grab content from URL
$pageRequest = fopen($url,"r");

//get content from request
$pageContent = stream_get_contents($pageRequest);

//close connection
fclose($pageRequest);

//regex to find all URLs in content
preg_match_all('/\shref=\"([^\"]*)\"(.*)/siU', $pageContent, $links, PREG_PATTERN_ORDER);

//loop through array and echo out the URL
foreach ($links[0] as $link){
	//create string
	$toFind = array(" ","href=","\""); //clean up the string
	$linkString = str_replace($toFind,"",$link);
	$finalString = "";// used to generate final string
	
	//look for links that do not have the protocol (http://)
	$ptf = $protocol == "https"?"http":$protocol;
	if(strpos($linkString,$ptf) === false){
		//create final URL
		$finalString = substr($linkString,0,1) != "/"?$url . "/" . $linkString:$domain . $linkString;
		//append protocol to the URL
		$finalString = $protocol."://" . $finalString;
	}else{
		//link already has protocol
		$finalString = $linkString;
	}
	echo $finalString . chr(10); //adds a carriage return after each URL
}