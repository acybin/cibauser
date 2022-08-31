<?php


namespace App;


class Dataset
{
    private $input;
    private $output;

    public function getOutput()
    {
        return $this->output;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setOutput($output): void
    {
        $this->output = Math::normalize($output);
    }

    public function setInput($input): void
    {
        $this->input = Math::normalize($input);
    }
}