<?php


namespace App;

use Exception;

class NeuralNetwork
{
    private $inputLayer;
    private $hiddenLayer;
    private $outputLayer;
    private $synapses;
    private $epsilon = 0.7;
    private $moment = 0.3;

    public function __construct(int $countInput, int $countHidden, int $countOutput)
    {
        for ($i = 0; $i < $countInput; $i++) {
            $this->inputLayer[] = new InputNeuron();
        }

        for ($i = 0; $i < $countHidden; $i++) {
            $this->hiddenLayer[] = new Neuron();
        }

        for ($i = 0; $i < $countOutput; $i++) {
            $this->outputLayer[] = new Neuron();
        }

        for ($i = 0; $i < $countInput; $i++) {
            for ($j = 0; $j < $countHidden; $j++) {
                $synapse = new Synapse($this->inputLayer[$i], $this->hiddenLayer[$j]);
                $synapse->setWeight(Math::weight());
                $this->synapses[] = $synapse;
            }
        }

        for ($i = 0; $i < $countHidden; $i++) {
            for ($j = 0; $j < $countOutput; $j++) {
                $synapse = new Synapse($this->hiddenLayer[$i], $this->outputLayer[$j]);
                $synapse->setWeight(Math::weight());
                $this->synapses[] = $synapse;
            }
        }
    }

    public function setEpsilon(float $epsilon): void
    {
        $this->epsilon = $epsilon;
    }

    public function setMoment(float $moment): void
    {
        $this->moment = $moment;
    }

    public function sendValues(array $array): array
    {
        $countInput = count($this->inputLayer);
        $synapses = $this->synapses;
        $output = [];

        if (count($array) < $countInput)
            throw new Exception('Массив данных слишком мал.');

        $array = array_slice($array, 0, $countInput);

        foreach ($this->inputLayer as $key => $inputNeuron) {
            $inputNeuron->setInput($array[$key]);
        }

        foreach ($this->hiddenLayer as $hiddenNeuron) {
            $hiddenNeuron->setInput(0);
        }

        foreach ($this->outputLayer as $outputNeuron) {
            $outputNeuron->setInput(0);
        }

        foreach ($synapses as $synapse) {
            $synapse->getRight()->addInput($synapse->getLeft()->getOutput() * $synapse->getWeight());
        }

        foreach ($this->outputLayer as $outputNeuron) {
            $output[] = $outputNeuron->getOutput();
        }

        return $output;
    }

    public function train(Dataset $dataset): array
    {
        $output = [];

        $epsilon = $this->epsilon;
        $moment = $this->moment;

        $inputDataset = $dataset->getInput();
        $outputDataset = $dataset->getOutput();

        foreach ($inputDataset as $dataKey => $inputValues) {

            try {
                $output[] = $this->sendValues($inputValues);
            } catch (Exception $e) {
                continue;
            }

            $originalOutput = $outputDataset[$dataKey];

            foreach ($this->outputLayer as $key => $outputNeuron) {
                $delta = ($originalOutput[$key] - $outputNeuron->getOutput()) * Math::derivative($outputNeuron->getOutput());
                $outputNeuron->setDelta($delta);
            }

            foreach ($this->hiddenLayer as $hiddenNeuron) {
                $hiddenNeuron->setDelta(0);
            }

            foreach ($this->inputLayer as $inputNeuron) {
                $inputNeuron->setDelta(0);
            }

            $synapses = array_reverse($this->synapses);

            foreach ($synapses as $synapse) {
                $left = $synapse->getLeft();
                $right = $synapse->getRight();
                $left->addDelta(Math::derivative($left->getOutput()) * ($synapse->getWeight() * $right->getDelta()));
            }

            foreach ($synapses as $synapse) {
                $deltaWeight = $epsilon * $synapse->grad() + ($moment * $synapse->getDeltaWeight());
                $synapse->setWeight($synapse->getWeight() + $deltaWeight);
            }
        }

        return $output;
    }

    public function saveToFile(string $filename): void
    {
        $str = [];
        foreach ($this->synapses as $synapse) {
            $str[] = implode("\t", [$synapse->getWeight(), $synapse->getDeltaWeight()]);
        }

        file_put_contents($filename, implode(PHP_EOL, $str));
    }

    public function loadFromFile(string $filename): void
    {
        $str = explode(PHP_EOL, file_get_contents($filename));
        foreach ($this->synapses as $key => $synapse) {

            $str[$key] = explode("\t", $str[$key]);

            $synapse->setWeight(floatval($str[$key][0]));
            $synapse->setDeltaWeight(floatval($str[$key][1]));
        }
    }
}