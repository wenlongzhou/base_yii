<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_user_role".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $role_id 角色id
 */
class AdminUserRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_user_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
        ];
    }
}
