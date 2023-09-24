<?php


namespace common\models\apple;


use common\behaviors\SoftDeleteBehavior;
use common\models\apple\states\AppleEatenState;
use common\models\apple\states\AppleFallenState;
use common\models\apple\states\AppleOnTreeState;
use common\models\apple\states\AppleRottenState;
use common\models\apple\states\AppleState;
use common\models\apple\states\AppleStateInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string $color
 * @property int $status
 * @property int|null $eaten_percent
 * @property int|null $created_at
 * @property int|null $fell_at
 */
class Apple extends BaseApple
{
    private AppleStateInterface $state;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $color = null, $config = [])
    {
        if ($this->isNewRecord) {
            $this->color = $color ?? $this->getRandomColor();
            $this->setState(new AppleOnTreeState($this));
        }
        parent::__construct($config);
    }

    public function afterFind()
    {

        if ($this->status === self::STATUS_FALLEN && $this->fell_at) {
            $sinceFall = time() - $this->fell_at;
            if ($sinceFall > 5 * 5) {
                $this->status = self::STATUS_ROTTEN;
                $this->save();
            }
        }

        switch ($this->status) {
            case self::STATUS_ON_TREE:
                $this->state = new AppleOnTreeState($this);
                break;
            case self::STATUS_FALLEN:
                $this->state = new AppleFallenState($this);
                break;
            case self::STATUS_ROTTEN:
                $this->state = new AppleRottenState($this);
                break;
            case self::STATUS_EATEN:
                $this->state = new AppleEatenState($this);
                break;
        }

        parent::afterFind();
    }

    /**
     * {@inheritdoc}
     */

/*    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->status === self::STATUS_FALLEN && $this->fell_at) {
                $sinceFall = time() - $this->fell_at;
                if ($sinceFall > 5 * 5) { //5 * 3600
                    $this->status = self::STATUS_ROTTEN;
                }
            }
            return true;
        }
        return false;
    }*/

    protected function getRandomColor(): string
    {
        $colors = ['red', 'green', 'yellow'];
        return $colors[array_rand($colors)];
    }

    public function setState(AppleStateInterface $state): void
    {
        if ($state instanceof AppleOnTreeState) {
            $this->status = self::STATUS_ON_TREE;
        } else if ($state instanceof AppleFallenState) {
            $this->status = self::STATUS_FALLEN;
        } else if ($state instanceof AppleRottenState) {
            $this->status = self::STATUS_ROTTEN;
        } else if ($state instanceof AppleEatenState) {
            $this->status = self::STATUS_EATEN;
        }
        $this->state = $state;
    }

    public function getState(): AppleStateInterface
    {
        return $this->state;
    }

    public function fall(): void
    {
        $this->state->fall();
    }

    public function eat(int $percent): void
    {
        $this->state->eat($percent);
    }

}