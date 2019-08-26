<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_tag`.
 */
class m190304_123507_create_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = '{{%tag}}';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'title' => $this->string(30)->notNull()->comment('Tag name')
        ]);

        $this->createIndex('index_title', $table, 'title', true);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tag}}');
    }
}
