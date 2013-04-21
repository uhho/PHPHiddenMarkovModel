# Hidden Markov Model & Viterbi algorithm for PHP #

A Hidden Markov Model is a mathematical system that undergoes transitions between finite states, where each is emiting a symbol (signal) with given probability.
Generally speaking, a hidden Markov model is like a finite state machine, but both transitions between states and emited symbols are not deterministic.
Therefore HMM is considered to be the simplest dynamic Bayesian network.

Hidden Markov Model can be used in:
- Part-of-speech tagging
- Speech, handwriting and gesture recognition
- Bioinformatics
- Analysing signals and processes

Learn more about Hidden Markov Model at http://en.wikipedia.org/wiki/Hidden_Markov_model

This library contain simple implementation of Viterbi algorithm which is used in searching state transitions for given observation set.

## Usage ##
```php
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

var_dump($HMM->viterbi(array('x', 'y', 'y', 'z', 'x', 'x')));
```

## Sample output ##
```php
array(
    0 => 'a',
    1 => 'b',
    2 => 'b',
    3 => 'b',
    4 => 'a',
    5 => 'a',
);
```