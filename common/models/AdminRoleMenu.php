<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_role_menu".
 *
 * @property int $id
 * @property int $role_id 角色ID
 * @property int $menu_id 菜单ID
 */
class AdminRoleMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_role_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'menu_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'menu_id' => 'Menu ID',
        ];
    }
}
