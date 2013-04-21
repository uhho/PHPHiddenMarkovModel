<?php

/**
 * Hidden Markov Model class
 *
 * @author Lukasz Krawczyk <contact@lukaszkrawczyk.eu>
 * @copyright Copyright © 2013 Lukasz Krawczyk
 * @license MIT
 * @link http:/www.lukaszkrawczyk.eu
 */
class HiddenMarkovModel {

    public $states;
    public $observations;
    public $startProb;
    public $transProb;
    public $emitProb;

    /**
     * Constructor
     *
     * @param array $states
     * @param array $observations
     * @param array $startProb
     * @param array $transProb
     * @param array $emitProb
     */
    public function __construct($states, $observations, $startProb, $transProb, $emitProb) {
        $this->states = $states;
        $this->observations = $observations;
        $this->startProb = $startProb;
        $this->transProb = $transProb;
        $this->emitProb = $emitProb;
    }

    /**
     * Viterbi algorithm
     * finding best path of hidden states based on observed events
     *
     * @return array
     */
    public function viterbi($observations) {
        $viterbi = array();
        $path = array();

        // start states
        foreach($this->states as $state) {
            $viterbi[0][$state] = isset($this->emitProb[$state][$observations[0]])
                ? $this->startProb[$state] * $this->emitProb[$state][$observations[0]]
                : 0;
            $path[$state] = array($state);
        }

        // generate path
        for($step = 1; $step < count($observations); $step++) {
            $newpath = array();
            // go through all states and choose the best possible path
            foreach($this->states as $state) {
                list($nextState, $prob) = $this->getNextState($viterbi, $state, $step, $observations);
                $viterbi[$step][$state] = $prob;
                $newpath[$state] = array_merge($path[$nextState], array($state));
            }
            $path = $newpath;
        }

        // generate final state
        list($state, $prob) = $this->getFinalState($viterbi, $observations);
        return array($prob, $path[$state]);
    }

    /**
     * Calculating best final step
     *
     * @param array $viterbi - list of viterbi states
     * @return array
     */
    private function getFinalState($viterbi, $observations) {
        $stateProbs = array();
        foreach($this->states as $state) {
            $stateProbs[$state] = $viterbi[count($observations) - 1][$state];
        }
        return $this->chooseBestState($stateProbs);
    }

    /**
     * Calculating best inner step
     *
     * @param array $viterbi - list of viterbi states
     * @param string $state - checked state
     * @param int $step - current state
     * @return array
     */
    private function getNextState($viterbi, $state, $step, $observations) {
        $stateProbs = array();
        foreach($this->states as $subState) {
            $index = (isset($this->emitProb[$state][$observations[$step]])
                    && isset($this->transProb[$subState][$state]))
                ? $viterbi[$step - 1][$subState] * $this->transProb[$subState][$state] * $this->emitProb[$state][$observations[$step]]
                : 0;
            $stateProbs[$subState] = $index;
        }
        return $this->chooseBestState($stateProbs);
    }

    /**
     * Sorting an array of states with probabilities
     * and choosing the best one
     *
     * @param array $states
     * @return array
     */
    private function chooseBestState($states) {
        arsort($states);
        $state = key($states);
        $prob = $states[$state];
        return array($state, $prob);
    }
}

?>