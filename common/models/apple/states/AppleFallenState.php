<?php


namespace common\models\apple\states;


use common\exceptions\IllegalStateTransitionException;

class AppleFallenState extends AppleState
{

    public function fall(): void
    {
        throw new IllegalStateTransitionException('Яблако уже упало с дерева');
    }

    public function eat(int $percent): void
    {
        $this->apple->eaten_percent += $percent;
        if ($this->apple->eaten_percent >= 100) {
            $this->apple->eaten_percent = 100;
            $this->apple->setState(new AppleEatenState($this->apple));
        }
    }

}