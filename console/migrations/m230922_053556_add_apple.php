<?php

use yii\db\Migration;

/**
 * Class m230922_053556_add_apple
 */
class m230922_053556_add_apple extends Migration
{

   private const TABLE_APPLE = '{{%apple}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_APPLE, [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
            'status' => $this->smallInteger()->unsigned()->notNull(),
            'eaten_percent' => $this->integer()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned(),
            'fell_at' => $this->integer()->unsigned(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_APPLE);
    }
}
