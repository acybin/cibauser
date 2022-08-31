<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Funca
{
    public function render(): Response
    {
        $nn = new NeuralNetwork(2, 2, 1);

        $dataset = new Dataset();
        $dataset->setInput([[0,0],[1,0],[1,0],[1,1]]);
        $dataset->setOutput([[0],[1],[1],[0]]);

       for ($i = 0; $i < 100000; $i++)
            $nn->train($dataset);

        print_r($nn->train($dataset));

        /*$nn = new NeuralNetwork(4, 12, 1);

        $dataset = new Dataset();
        $dataset->setInput([[2022,8,1,1],[2022,8,1,2],[2022,8,1,3],[2022,8,1,4]]);
        $dataset->setOutput([[1519],[1410],[1482],[1166]]);

        $nn->loadFromFile('aa');

        for ($i = 0; $i < 20000; $i++)
            $nn->train($dataset);

        //print_r(Math::normalize($nn->train($dataset)));

        print_r(Math::normalize($nn->train($dataset)));

        $nn->saveToFile('aa');*/

        return new Response('olga');
    }
}