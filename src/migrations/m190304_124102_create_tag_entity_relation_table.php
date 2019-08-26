<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_tag_entity_relation`.
 */
class m190304_124102_create_tag_entity_relation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table = '{{%tag_entity_relation}}';
        $this->createTable($table, [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('ID tag'),
            'entity' => $this->string(60)->null()->comment('The name of the class to use the tag'),
            'entity_id' => $this->integer(11)->defaultValue(0)->comment('The primary key of the class using the tag'),
        ]);

        $this->createIndex('index_tag_id', $table, 'tag_id');
        $this->createIndex('index_entity_entity_id', $table, ['entity', 'entity_id']);
        $this->createIndex('unique_tag_id_entity_id', $table, ['tag_id', 'entity', 'entity_id'], true);

        $this->addForeignKey(
            'foreign_tag_id_entity_id',
            '{{%tag_entity_relation}}',
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
        $this->dropTable('{{%tag_entity_relation}}');
    }
}
