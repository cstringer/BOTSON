#!/usr/bin/env php
<?php
define('NUM_SENTENCES',  1);
define('NUM_PARAGRAPHS', 1);
define('FREQ_COMPOUND',  2);
define('FREQ_CONJ',      4);

// load word lists from external files
$verbs = file('wl-verbs.txt');
$preps = file('wl-preps.txt');
$jargon = file('wl-ux-terms.txt');
$cal = file('wl-cal.txt');
$conj = file('wl-conj.txt');

// sentence types
$sentenceTypes = array('SV', 'SVO', 'SVAO', 'SVP');

// end-of-sentence punctuation
$eosPunctuation = array('.','!','.','?','.','...');

// set script error reporting level
error_reporting(0);

// seed random number generator
srand();

/** Main */
main();
function main() {
    global $argc, $argv, $sentenceTypes;

    // number of sentences
    $numSentences = NUM_SENTENCES;

    // number of paragraphs
    $numParagraphs = NUM_PARAGRAPHS;

    // should it be a compound sentence?
    $compound = randomBool(FREQ_COMPOUND);

    // if so, use a conjunction?
    $useConj = randomBool(FREQ_CONJ, TRUE);

    // randomize all options
    $randomizeAll = FALSE;

    // default to random sentence type(s)
    $type = NULL;

    // process command-line args
    for ($a = 1; $a < $argc; $a++) {
        switch($argv[$a]) {
            case '-n':
            case '--num-sentences':
                $numSentences = $argv[++$a];
                break;

            case '-p':
            case '--num-paragraphs':
                $numParagraphs = $argv[++$a];
                break;

            case '-c':
            case '--compound':
                $compound = TRUE;
                break;

            case '-j':
            case '--use-conjunction':
                $useConj = TRUE;
                break;

            case '-r':
            case '--randomize':
                $randomizeAll = TRUE;
                break;

            case '-t':
            case '--type':
                $t = $argv[++$a];
                if (in_array($t, $sentenceTypes)) {
                    $type = $t;
                    $numSentences = 1;
                    $numParagraphs = 1;
                }
                break;
        }
    }

    for (; $numParagraphs > 0; $numParagraphs--) {
        for ($n = $numSentences; $n > 0; $n--) {
            $sentence = array();
            if ($randomizeAll) {
                $compound = randomBool(FREQ_COMPOUND);
                $useConj = randomBool(FREQ_CONJ, TRUE);
                $type = NULL;
            }
            buildSentence($sentence, $type, $compound, $useConj);
            printSentence($sentence);
            if ($numSentences > 1) {
                $randomizeAll = TRUE;
                echo " ";
            }
        }
        if ($numParagraphs > 1) {
            echo "\n";
        }
        echo "\n";
    }

    exit();
}

/** Build a sentence of the given type */
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

    /* For compound sentences: either add a comma after the last word
     *  and add a conjunction, or only add a semicolon after last word;
     *  recurse to add another sentence */
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

/**
 * Print the sentence words (first capitalized) separated by spaces
 *  with the given (or random) end-of-sentence punctuation
 */
function printSentence($sentence, $es_punc = NULL) {
    global $eosPunctuation;
    $sentString = '';
    if (!$es_punc) {
        $es_punc = getRandom($eosPunctuation);
    }
    $sentString = implode(' ', $sentence) . $es_punc;
    if (!preg_match('/^[a-z]{1}[A-Z]{1}/', $sentString)) {
        $sentString = ucfirst($sentString);
    }
    echo $sentString;
}

/** Get a random entry from the given array (with any EOL whitespace trimmed) */
function getRandom($array = array()) {
    return rtrim($array[array_rand($array)]);
}

/** Produce a random boolean, weighted by frequency with optional exclusivity */
function randomBool($freq = 2, $exclusive = FALSE) {
    $rb = (rand() % $freq == 0) ? TRUE : FALSE;
    return $exclusive ? !$rb : $rb;
}

?>
