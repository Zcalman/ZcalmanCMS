<?PHP
function wildheart_month($nr) {
	$array = array(
		"1" => "januari",
		"2" => "februari",
		"3" => "mars",
		"4" => "april",
		"5" => "maj",
		"6" => "juni",
		"7" => "juli",
		"8" => "augusti",
		"9" => "september",
		"10" => "oktober",
		"11" => "november",
		"12" => "december"
	);
	
	return $array[$nr];
}
?>