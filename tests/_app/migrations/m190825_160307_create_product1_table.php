<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_product1`.
 */
class m190825_160307_create_product1_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = '{{%product1}}';
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
        $this->dropTable('{{%product1}}');
    }
}
