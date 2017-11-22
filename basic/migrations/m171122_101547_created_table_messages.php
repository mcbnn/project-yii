<?php

use yii\db\Migration;

/**
 * Class m171122_101547_created_table_messages
 */
class m171122_101547_created_table_messages extends Migration
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
        echo "m171122_101547_created_table_messages cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('messages', array(
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'text' => 'text',
            'user_id' => 'integer NOT NULL',
        ));

        $this->addForeignKey("fk_message_user", "messages", "user_id", "user", "id", "CASCADE", "RESTRICT");
    }

    public function down()
    {
        $this->execute('  alter table messages drop foreign key fk_message_user');
        $this->dropTable('messages');
    }

}
