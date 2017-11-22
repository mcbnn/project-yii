<?php

use yii\db\Migration;

/**
 * Class m171122_105506_created_table_status
 */
class m171122_105506_created_table_status extends Migration
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
        echo "m171122_105506_created_table_status cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('status', array(
            'id' => 'pk',
            'name' => 'string NOT NULL',
        ));

        $this->execute('ALTER TABLE messages ADD status_id INT(1) NOT NULL;');
        $this->execute('INSERT INTO status (id, name) VALUES (1, \'на рассмотрении\');');
        $this->execute('INSERT INTO status (id, name) VALUES (2, \'в работе\');');
        $this->execute('INSERT INTO status (id, name) VALUES (3, \'выполено\');');

        $this->addForeignKey("fk_message_status", "messages", "status_id", "status", "id", "CASCADE", "RESTRICT");
    }

    public function down()
    {
        $this->dropTable('status');
    }

}
