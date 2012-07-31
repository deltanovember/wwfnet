<?php
$q = @$_GET['q'];
$target = @$_GET['target'];
$results = get_suggestions($q);

echo !empty($results)? print_results($results) : "";

function print_results($results) {
    global $target, $q;
	$response = "<ul>";
	for($i=0;$i<count($results);$i++){
		$url = @str_ireplace("%s", urlencode($results[$i]), $_GET['url']);
		$response .= '<li><a href="'.$url.'" target="'.$target.'">'.str_ireplace($q, $q.'<strong>', $results[$i]).'</strong></a></li>';
	}
	$response .= "</ul>";
    return $response;
}

function get_suggestions($q){
	$request_uri = 'http://www.google.com/complete/search?hl=en&xml=true&q='.urlencode($q);

    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $request_uri);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$xml = json_decode(json_encode(new SimplexmlElement(curl_exec($ch))), true);
	curl_close($ch);

	$results = @$xml['CompleteSuggestion'];
	$result = array();

	if(@$results['suggestion']) {
		$result[0] = $results['suggestion']['@attributes']['data'];
	} else {
		for($i=0;$i<count($results);$i++){
			$result[] = $results[$i]['suggestion']['@attributes']['data'];
		}
	}

	return $result;
}
?>