<?php

class m161215_094943_initial extends CDbMigration
{
	public function up()
	{
        $this->createTable('users', [
            'user_id' => 'pk',
            'name' => 'string not null',
            'email' => 'string not null',
            'password' => 'string not null',
            'active' => 'boolean not null default 1',
        ]);

        $this->createTable('products', [
            'product_id' => 'pk',
            'name' => 'string not null',
            'description' => 'text not null',
            'image_ext' => 'string',
            'price' => 'money',
            'currency_id' => 'integer not null',
            'created_at' => 'datetime default CURRENT_TIMESTAMP',
            'modified_at' => 'datetime default CURRENT_TIMESTAMP',
        ]);

        $this->createTable('orders', [
            'order_id' => 'pk',
            'email' => 'string not null',
            'product_id' => 'integer not null',
            'price' => 'money',
            'currency_id' => 'integer not null',
            'created_at' => 'datetime default CURRENT_TIMESTAMP',
            'modified_at' => 'datetime default CURRENT_TIMESTAMP',
            'status' => 'integer not null default 0',
        ]);

        $this->createTable('currency', [
            'currency_id' => 'pk',
            'code' => 'string not null',
            'ratio' => 'float',
        ]);

        $this->addForeignKey('products_currency_fk_1', 'products', 'currency_id', 'currency', 'currency_id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('orders_products_fk_1', 'orders', 'product_id', 'products', 'product_id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('orders_currency_fk_1', 'orders', 'currency_id', 'currency', 'currency_id', 'RESTRICT', 'CASCADE');

        $this->insert('users', ['name' => 'John Doe', 'email' => 'admin@admin.com', 'password' => '21232f297a57a5a743894a0e4a801fc3']);
        $this->insert('currency', ['code' => 'USD', 'ratio' => '1']);
        $this->insert('currency', ['code' => 'EUR', 'ratio' => '1.3']);

        return true;
	}

	public function down()
	{
		$this->dropTable('users');
		$this->dropTable('products');
		$this->dropTable('orders');
		$this->dropTable('currency');

		return true;
	}
}