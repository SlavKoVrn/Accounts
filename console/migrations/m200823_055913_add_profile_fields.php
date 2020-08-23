<?php

use yii\db\Migration;

/**
 * Class m200823_055913_add_profile_fields
 */
class m200823_055913_add_profile_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'description');
    }

}
