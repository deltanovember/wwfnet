<?php
$q = @$_GET['q'];
$request_uri = "http://ajax.googleapis.com/ajax/services/search/web?hl=en&v=1.0&key=ABQIAAAAT6lV7pDvZ3GvlMTTF6LUgBT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQwM--ieg_0fQ_8s2kfKMcZRslt0g&q=".urlencode($q);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request_uri);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$json = json_decode(curl_exec($ch), true);
curl_close($ch);

$results = $json['responseData']['results'];

echo !$q? "" : (!empty($results)? print_results($results) : "No results found for: <strong>$q</strong>");

function print_results($results) {
    global $q;
	$response = '<ul>';
	for($i=0; $i<count($results); $i++) {
		$response .= '<li><a href="'.$results[$i]['url'].'">'.$results[$i]['title'].'</a><span>'.$results[$i]['content'].'</span><em>'.$results[$i]['visibleUrl'].'</em></li>';
	}
	$response .= '</ul><a href="http://google.com/search?q='.urlencode($q).'" target="_blank">More results...</a>';
    
    return $response;
}
?>