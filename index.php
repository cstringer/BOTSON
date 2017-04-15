<?php

$numSent = 1;
if (isset($_GET['s']) && preg_match('/^[0-9]+$/', $_GET['s'])) {
    $numSent = $_GET['s'];
}

$numPara = 1;
if (isset($_GET['p']) && preg_match('/^[0-9]+$/', $_GET['p'])) {
    $numPara = $_GET['p'];
}

$output = rtrim(`./botson.php -n $numSent -p $numPara`);
$output = str_replace("\n","<br>",$output);

header('Content-type: text/json; charset=utf-8');
echo json_encode(
	array(
        'text' => $output
	)
);

exit();

?>
