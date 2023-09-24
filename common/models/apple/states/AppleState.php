<?php


namespace common\models\apple\states;

use common\models\apple\Apple;

abstract class AppleState implements AppleStateInterface
{
    protected Apple $apple;

    public function __construct(Apple $apple) {
        $this->apple = $apple;
    }

    abstract public function fall(): void;

    abstract public function eat(int $percent): void;
}