<?php

use yii\db\Migration;

/**
 * Class m230727_015511_add_auth_key_to_user
 */
class m230727_015511_add_auth_key_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'auth_key', $this->string(32)->notNull()->unique());
        $this->addColumn('{{%user}}', 'created_at', $this->integer(11));
        $this->addColumn('{{%user}}', 'updated_at', $this->integer(11));    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'auth_key');
        $this->dropColumn('{{%user}}', 'created_at');
        $this->dropColumn('{{%user}}', 'updated_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230727_015511_add_auth_key_to_user cannot be reverted.\n";

        return false;
    }
    */
}
