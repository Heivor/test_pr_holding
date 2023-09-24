<?php


namespace common\models\apple\states;


interface AppleStateInterface {

    public function fall(): void;

    public function eat(int $percent): void;

}