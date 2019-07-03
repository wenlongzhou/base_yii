<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_menu".
 *
 * @property int $id
 * @property string $title 菜单名
 * @property string $route 路由
 * @property int $level 0：菜单组 1：菜单 2：按钮
 * @property int $pid 父级菜单
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'pid'], 'integer'],
            [['title'], 'string', 'max' => 15],
            [['route'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'route' => 'Route',
            'level' => 'Level',
            'pid' => 'Pid',
        ];
    }
}
