<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_tag_weight`.
 */
class m190304_124102_create_tag_weight_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = '{{%tag_weight}}';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('ID tag'),
            'entity' => $this->string(60)->null()->comment('The name of the class that uses the tag'),
            'weight' => $this->integer(11)->notNull()->defaultValue(0)->comment('Number of uses'),
            'clicks' => $this->integer(11)->notNull()->defaultValue(0)->comment('Number of clicks by tag'),
        ]);

        $this->createIndex('index_tag_id', $table, 'tag_id');
        $this->createIndex('index_entity', $table, 'entity');
        $this->createIndex('index_weight', $table, 'weight');
        $this->createIndex('index_clicks', $table, 'clicks');
        $this->createIndex('unique_tag_id_entity', $table, ['tag_id', 'entity'], true);

        $this->addForeignKey(
            'foreign_tag_id_entity_weight',
            '{{%tag_weight}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%tag_weight}}');
    }
}
