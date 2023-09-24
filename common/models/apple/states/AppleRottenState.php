<?php


namespace common\models\apple\states;


use common\exceptions\IllegalStateTransitionException;

class AppleRottenState extends AppleState
{

    /**
     * @throws IllegalStateTransitionException
     */
    public function fall(): void
    {
        throw new IllegalStateTransitionException('Яблако уже упало с дерева');
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function eat(int $percent): void
    {
        throw new IllegalStateTransitionException('Нельзя есть гнилое яблоко');
    }

}