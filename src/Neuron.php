<?php


namespace App;


class Neuron
{
    protected $input;
    protected $delta;

    public function setInput(float $input): self
    {
        $this->input = $input;
        return $this;
    }

    public function addInput(float $input): self
    {
        $this->setInput(floatval($this->getInput()) + $input);
        return $this;
    }

    public function getInput(): ?float
    {
        return $this->input;
    }

    public function getOutput(): ?float
    {
        return Math::sigmoid(floatval($this->getInput()));
    }

    public function setDelta(float $delta): self
    {
        $this->delta = $delta;
        return $this;
    }

    public function addDelta(float $delta): self
    {
        $this->setDelta(floatval($this->getDelta()) + $delta);
        return $this;
    }

    public function getDelta(): ?float
    {
        return $this->delta;
    }
}