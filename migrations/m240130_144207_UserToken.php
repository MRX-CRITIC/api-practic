<?php

use yii\db\Migration;

/**
 * Class m240130_144207_UserToken
 */
class m240130_144207_UserToken extends Migration
{
    public function safeUp()
    {
        $this->createTable('users_token', [
            'ip' => $this->primaryKey(),
            'user_agent' => $this->string(200)->null(),
            'user_ip' => $this->string(100)->notNull(),
            'access_token' => $this->string(200)->notNull(),
            'refresh_token' => $this->string(200)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'lifetime_access' => $this->integer()->notNull(),
            'lifetime_refresh' => $this->integer()->notNull(),
            'create_time' => $this->timestamp()->defaultExpression('NOW()')->notNull()
        ]);

        $this->addForeignKey(
            'user_to_token_fk',
            'users_token',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE',
        );
    }

    public function safeDown()
    {
        $this->dropTable('users_token');
    }
}
