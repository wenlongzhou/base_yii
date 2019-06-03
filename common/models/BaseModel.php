<?php

namespace common\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{

    /**
     * 设置场景的安全属性
     * 当当前场景为login时候，只有username and password 可被块赋值， 其他属性不会被赋值。
     *
     * 场景作为属性来设置
     * $model = new User;
     * $model->scenario = 'login';
     * 场景通过构造初始化配置来设置
     * $model = new User(['scenario' => 'login']);
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'login' => ['username', 'password'],
            'register' => ['username', 'email', 'password'],
        ];
    }

    /**
     * 提供一个特别的别名为 safe 的验证器来申明 哪些属性是安全的不需要被验证
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'safe'],
        ];
    }


}