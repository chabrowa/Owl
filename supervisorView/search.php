<?php
    $connect = mysql_connect("localhost", "root", "") or die("couldn't connect to the database");
    mysql_select_db("qwerty") or die("couldn't find database'");

$html = '';
$html .= '<li class="result">';
$html .= '<a target="_blank" href="urlString">';
$html .= '<h3>nameString</h3>';

$html .= '</a>';
$html .= '</li>';

// Get Search
$search_string = preg_replace("/[^A-Za-z0-9]/", " ", $_POST['query']);
$search_string = mysql_real_escape_string($search_string);

// Check Length More Than One Character
if (strlen($search_string) >= 1 && $search_string !== ' ') {
	// Build Query
	$query = 'SELECT * FROM country WHERE name LIKE "%'.$search_string.'%"';

	// Do Search
	$result = mysql_query($query);
	//exit;
	while($results = mysql_fetch_array($result)) {
		$result_array[] = $results;
	}
//exit;
	// Check If We Have Results
	if (isset($result_array)) {
		foreach ($result_array as $result) {

			// Format Output Strings And Hightlight Matches
			//
			$display_name = preg_replace("/".$search_string."/i", "<b class='highlight'>".$search_string."</b>", $result['name']);
			$display_url = 'http://php.net/manual-lookup.php?pattern='.urlencode($result['name']).'&lang=en';

			// Insert Name
			$output = str_replace('nameString', $display_name, $html);

			// Insert URL
			$output = str_replace('urlString', $display_url, $output);

			// Output
			echo($output);
		}
	}else{

		// Format No Results Output
		$output = str_replace('urlString', 'javascript:void(0);', $html);
		$output = str_replace('nameString', '<b>No Results Found.</b>', $output);
		$output = str_replace('funcStr', 'Sorry :(', $output);

		// Output
		echo($output);
	}
}

?>