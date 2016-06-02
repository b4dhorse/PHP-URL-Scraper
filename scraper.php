<?php
/*
Use this to find URLs in content that are contained an href, e.g. href="domain.com"
*/

$url = "http://www.vexingmedia.com"; //URL to scrape
$protocol = substr($url,0,strpos($url,":")); //get protocol
$domain = str_replace(" ","",parse_url($url,PHP_URL_HOST)); //get main domain
$pageRequest = fopen($url,"r"); //grab content from URL

$pageContent = stream_get_contents($pageRequest); //get content from request
fclose($pageRequest); //close connection

//regex to find all URLs in content, returns href="$url"
preg_match_all('/\shref=\"([^\"]*)\"(.*)/siU', $pageContent, $links, PREG_PATTERN_ORDER);

//loop through array and echo out the URL
foreach ($links[0] as $link){
	$toFind = array(" ","href=","\""); //items to replace
	$linkString = str_replace($toFind,"",$link); //clean up the string
	$finalString = $linkString; // used to generate final string
	
	//look for links that do not have the protocol
	//this means they could be relative or absolute links
	$ptf = $protocol == "https"?"http":$protocol;
	if(strpos($linkString,$ptf) === false){
		//create final URL
		//check to see if the link is absolute to the domain, or relative
		$finalString = substr($linkString,0,1) != "/"?$url . "/" . $linkString:$domain . $linkString;
		//append protocol to the URL
		$finalString = $protocol."://" . $finalString;
	}
	echo $finalString . chr(10); //adds a carriage return after each URL
}