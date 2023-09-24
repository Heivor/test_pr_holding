<?php


namespace common\models\apple;


use common\behaviors\SoftDeleteBehavior;
use common\models\apple\states\AppleEatenState;
use common\models\apple\states\AppleFallenState;
use common\models\apple\states\AppleOnTreeState;
use common\models\apple\states\AppleRottenState;
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
class BaseApple extends ActiveRecord
{
    public const STATUS_ON_TREE = 1;
    public const STATUS_FALLEN = 2;
    public const STATUS_ROTTEN = 3;
    public const STATUS_EATEN = 4;

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ON_TREE => 'На дереве',
            self::STATUS_FALLEN => 'На земле',
            self::STATUS_ROTTEN => 'Сгнило',
            self::STATUS_EATEN => 'Съедено',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['color', 'status'], 'required'],
            [['status', 'eaten_percent', 'fell_at'], 'integer'],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'color' => 'Цвет',
            'status' => 'Статус',
            'eaten_percent' => '% съеденного',
            'created_at' => 'Дата появления',
            'fell_at' => 'Дата падения',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
    }

}