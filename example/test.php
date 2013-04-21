<?php

require_once('../lib/HiddenMarkovModel.php');

$observations = array('x', 'y', 'z');
$states = array('a', 'b');
$startProb = array('a' => 0.6, 'b' => 0.4);
$transProb = array(
    'a' => array(
        'a' => 0.2,
        'b' => 0.8
    ),
    'b' => array(
        'a' => 0.5,
        'b' => 0.5
    )
);
$emitProb = array(
    'a' => array(
        'x' => 0.6,
        'y' => 0.2,
        'z' => 0.1
    ),
    'b' => array(
        'x' => 0.1,
        'y' => 0.4,
        'z' => 0.5
    )
);

$HMM = new HiddenMarkovModel(
        $states,
        $observations,
        $startProb,
        $transProb,
        $emitProb
);

$viterbi = $HMM->viterbi(array('x', 'y', 'y', 'z', 'x', 'x'));
var_dump($viterbi[1]);

?>