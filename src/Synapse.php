<?php


namespace App;


class Synapse
{
    private $left;
    private $right;
    private $weight;
    private $deltaWeight;

    public function __construct(Neuron $left, Neuron $right)
    {
        $this->setLeft($left);
        $this->setRight($right);
    }

    public function setLeft(Neuron $left): void
    {
        $this->left = $left;
    }

    public function setRight(Neuron $right): void
    {
        $this->right = $right;
    }

    public function setWeight(float $weight): void
    {
        $prev = $this->getWeight();
        $this->weight = $weight;

        if (null !== $prev) $this->deltaWeight = $this->getWeight() - $prev;
    }

    public function getLeft(): ?Neuron
    {
        return $this->left;
    }

    public function getRight(): ?Neuron
    {
        return $this->right;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function grad(): float
    {
        return  $this->left->getOutput() * $this->right->getDelta();
    }

    public function getDeltaWeight(): float
    {
        return floatval($this->deltaWeight);
    }

    public function setDeltaWeight(float $deltaWeight): void
    {
        $this->deltaWeight = $deltaWeight;
    }
}