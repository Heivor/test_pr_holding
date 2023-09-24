<?php


namespace common\models\apple\states;


use common\exceptions\IllegalStateTransitionException;

class AppleEatenState extends AppleState
{

    public function fall(): void
    {
        throw new IllegalStateTransitionException('Яблако съедено');
    }

    public function eat(int $percent): void
    {
        throw new IllegalStateTransitionException('Яблако уже съедено');
    }

}