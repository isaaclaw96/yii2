<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%wallet}}`.
 */
class m230725_082413_create_wallet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%wallet}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned()->notNull(), 
            'amount' => $this->decimal(10, 2)->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
        ]);

        // Add foreign key constraint to link user_id to the user table
        $this->addForeignKey(
            'fk-wallet-user',
            'wallet',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign key constraint first
        $this->dropForeignKey('fk-wallet-user', 'wallet');
        $this->dropColumn('{{%wallet}}', 'created_at');
        $this->dropColumn('{{%wallet}}', 'updated_at');

        // Drop the wallet table
        $this->dropTable('wallet');
    }
}
