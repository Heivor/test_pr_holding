<?php


namespace common\models\apple\states;


use common\exceptions\IllegalStateTransitionException;

class AppleOnTreeState extends AppleState
{

    public function fall(): void
    {
        $this->apple->fell_at = time();
        $this->apple->setState(new AppleFallenState($this->apple));
    }

    public function eat(int $percent): void
    {
        throw new IllegalStateTransitionException('Яблоко ещё на дереве');
    }

}