<?php

namespace common\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class PermissionBehavior extends Behavior
{

    public $actions = [];

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     *
     * @param \yii\base\ActionEvent $event
     * @return boolean
     * @throws ForbiddenHttpException
     */
    public function beforeAction($event)
    {
        $controller = $event->action->controller->id; //获取到控制器
        $action = $event->action->id; //获取到action

        //验证权限
        $access = $controller . '/' . $action;  //权限name

        //超级管理员不需要验证权限 ,以后这里可以添加不需要验证的用户
        if (Yii::$app->user->id == 100) return true;

        if (!Yii::$app->user->can($access)) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return true;

    }
}