<?php

use yii\db\Migration;

/**
 * Class m171122_111244_created_table_answers
 */
class m171122_111244_created_table_answers extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171122_111244_created_table_answers cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('answers', array(
            'id' => 'pk',
            'text' => 'text',
            'user_id' => 'integer NOT NULL',
            'message_id' => 'integer NOT NULL',
        ));

        $this->addForeignKey("fk_answers_user", "answers", "user_id", "user", "id", "CASCADE", "RESTRICT");
        $this->addForeignKey("fk_answers_messages", "answers", "message_id", "messages", "id", "CASCADE", "RESTRICT");
    }

    public function down()
    {
        $this->dropTable('answers');
    }

}
