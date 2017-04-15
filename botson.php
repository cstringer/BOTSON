#!/usr/bin/php -f
<?php
error_reporting(E_ALL);
srand();

// sentence types
$sentenceTypes = array('SV', 'SVO', 'SVAO', 'SVP');

// use a random sentence type
$senType = getRandom($sentenceTypes);

// should it be a compound sentence?
$compound = (rand() % 2 == 0) ? TRUE : FALSE;

// if so, use a conjunction?
$useConj = (rand() % 4 == 0) ? FALSE : TRUE;

// load word lists from external files
$verbs = file('wl-verbs.txt');
$preps = file('wl-preps.txt');
$jargon = file('wl-ux-terms.txt');
$cal = file('wl-cal.txt');
$conj = file('wl-conj.txt');

// end-of-sentence punctuation
$eosPunctuation = array('.','!','?','.');

// main
$sentence = array();
buildSentence($sentence, $senType, $compound, $useConj);
printSentence($sentence);

// build a sentence of the given type
function buildSentence(&$sentence, $type = NULL, $compound = FALSE, $useConj = TRUE) {
    global $sentenceTypes, $cal, $verbs, $jargon, $preps, $conj;
    if (!$type) {
        $type = getRandom($sentenceTypes);
    }
    switch ($type) {
        case 'SVO':
            $sentence[] = getRandom($cal);
            $sentence[] = getRandom($verbs);
            $sentence[] = getRandom($jargon);
            break;

        case 'SVAO':
            $sentence[] = getRandom($cal);
            $sentence[] = getRandom($verbs);
            $sentence[] = getRandom($cal);
            $sentence[] = getRandom($jargon);
            break;

        case 'SVP':
            $sentence[] = getRandom($cal);
            $sentence[] = getRandom($verbs);
            $sentence[] = getRandom($preps);
            $sentence[] = getRandom($jargon);
            break;

        case 'SV':
        default:
            $sentence[] = getRandom($cal);
            $sentence[] = getRandom($verbs);
            break;
        }

    /* For compound sentences, either add a comma after the last word
     *  and add a conjunction, or only add a semicolon after last word
     *  and recurse to add another sentence */
    if ($compound) {
        $lastWord = array_pop($sentence);
        if ($useConj) {
            $sentence[] = $lastWord . ',';
            $sentence[] = getRandom($conj);
        } else {
            $sentence[] = $lastWord . ';';
        }
        buildSentence($sentence);
    }
}

/* Print the sentence words (first capitalized) separated by spaces
 *  with the given (or random) end-of-sentence punctuation */
function printSentence($sentence, $es_punc = NULL) {
    global $eosPunctuation;
    if (!$es_punc) {
        $es_punc = getRandom($eosPunctuation);
    }
    echo ucfirst(implode(' ', $sentence)) . $es_punc . "\n";
}

// get a random entry from the given array (with any EOL whitespace trimmed)
function getRandom($array = array()) {
    return rtrim($array[array_rand($array)]);
}

?>
