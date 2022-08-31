<?php


namespace App;


class InputNeuron extends Neuron
{
    public function getOutput(): ?float
    {
        return $this->input;
    }
}