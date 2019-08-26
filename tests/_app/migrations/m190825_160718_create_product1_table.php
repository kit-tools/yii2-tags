<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_product2`.
 */
class m190825_160718_create_product1_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = '{{%product2}}';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'title' => $this->string(30)->notNull()->comment('Product name')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product2}}');
    }
}
