<?php

use yii\db\Migration;

class m231212_142151_BD_user_basis extends Migration
{
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(100)->unique()->notNull(),
            'password' => $this->string(100)->notNull(),
            'is_admin' => $this->boolean()->defaultValue(false)->notNull(),
        ]);

        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'fio' => $this->string(100)->notNull(),
            'phone' => $this->integer()->notNull(),
            'address' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull(),
        ]);
        $this->addForeignKey(
            'users_to_user_profile_fk',
            'user_profile',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE',
        );

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'date' => $this->timestamp()->defaultExpression('NOW()')->notNull(),
            'price' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'users_to_orders_fk',
            'orders',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE',
        );

    }

    public function safeDown()
    {
        $this->dropTable('users');
        $this->dropTable('user_profile');
        $this->dropTable('orders');
    }

}
