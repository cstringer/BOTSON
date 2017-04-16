<?php
// number of sentences
$numSent = 1;
if (isset($_GET['s']) && preg_match('/^[0-9]+$/', $_GET['s'])) {
    $numSent = $_GET['s'];
}

// number of paragraphs
$numPara = 1;
if (isset($_GET['p']) && preg_match('/^[0-9]+$/', $_GET['p'])) {
    $numPara = $_GET['p'];
}

// call command-line script, capture output, remove trailing newling
$output = rtrim(`./botson.php -n $numSent -p $numPara`);

// convert remiaing newlines to linebreaks
$output = nl2br($output);

// output JSON-encoded object
header('Content-type: text/json; charset=utf-8');
echo json_encode(array('text' => $output));

?>
