<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link}}`.
 */
class m220701_114554_create_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link}}', [
            'id' => $this->bigPrimaryKey(),
            'target' => $this->string()->notNull(),
            'hash' => $this->string()->notNull()->unique(),
            'use_count' => $this->bigInteger()->unsigned()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%link}}');
    }
}
