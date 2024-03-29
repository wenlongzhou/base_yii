<?php

namespace common\components;

use Yii;
use yii\base\UserException;
use yii\base\Exception;
use yii\base\ErrorException;
use yii\web\Response;
use yii\web\HttpException;
use yii\web\ForbiddenHttpException;
use yii\helpers\Html;

class ErrorHandler extends \yii\web\ErrorHandler
{
    const GENERAL = -1;
    const RE_LOGIN = -2;

    /**
     * @see \yii\web\ErrorHandler::renderException()
     */
    public function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $useErrorView = $response->format === Response::FORMAT_HTML && (!YII_DEBUG || $exception instanceof UserException);

        if ($useErrorView && $this->errorAction !== null) {
            $result = Yii::$app->runAction($this->errorAction);
            if ($result instanceof Response) {
                $response = $result;
            } else {
                $response->data = $result;
            }
        } elseif ($response->format === Response::FORMAT_HTML) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' || YII_ENV_TEST) {
                // AJAX request
                $response->data = '<pre>' . $this->htmlEncode($this->convertExceptionToString($exception)) . '</pre>';
            } else {
                // if there is an error during error rendering it's useful to
                // display PHP error in debug mode instead of a blank screen
                if (YII_DEBUG) {
                    ini_set('display_errors', 1);
                }
                $file = $useErrorView ? $this->errorView : $this->exceptionView;
                $response->data = $this->renderFile($file, [
                    'exception' => $exception,
                ]);
            }
        } else {
            $response->data = $this->convertExceptionToArray($exception);
        }

        // http状态码统一用200，避免客户端处理麻烦，错误内容在返回内容的code中体现
        // 后面看是否需要对网站做处理
        $response->setStatusCode(200);

        $response->send();
    }

    /**
     * 重写抛出的异常数据的数据结构
     * @see \yii\web\ErrorHandler::convertExceptionToArray()
     */
    public function convertExceptionToArray($exception)
    {
        // 非debug模式下的非用户级的异常将模糊提示，避免暴露服务端信息
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            \Yii::error('内部错误[' . \Yii::$app->requestedRoute . ']：exception' . get_class($exception) . ', message:' . $exception->getMessage() . ', Code: ' . $exception->getCode() . ', File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ', Trace: ' . $exception->getTraceAsString() . ', info: ' . json_encode(\Yii::$app->controller->params()), 'Exception');
            $exception = new HttpException(500, '服务器繁忙，请稍后再试！');
        }

        if ($exception instanceof ForbiddenHttpException) { // 未登录code为-2
            $code = -2;
            $message = "请重新登录";
        } else if ($exception->getCode() == 0) { // 特殊处理，所有默认exception为0时，返回给客户端都置为-1
            $code = -1;
            $message = $exception->getMessage();
        } else {
            $code = $exception->getCode();
            $message = $exception->getMessage();
        }

        $array = [
            'message' => $message,
            //'info' => $message,
            'data' => [],
            'code' => $code,
        ];

        // debug模式下，多一些错误信息
        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof \yii\db\Exception) {
                    $array['error-info'] = $exception->errorInfo;
                }
            } else {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
            }
        }
        if (($prev = $exception->getPrevious()) !== null) {
            $array['previous'] = $this->convertExceptionToArray($prev);
        }

        // 如果是jsonp
        if (Yii::$app->getResponse()->format === Response::FORMAT_JSONP) {
            $array = [
                'data' => $array,
                'callback' => Html::encode(Yii::$app->getRequest()->get('callback')),
            ];
        }
        if (-2 == $code) {
            $array = [
                'code' => -2,
                'message' => '请重新登录',
                'data' => ['item' => []],
            ];
        }

        return $array;
    }

    /**
     * Logs the given exception
     * @param \Exception $exception the exception to be logged
     */
    public function logException($exception)
    {
        parent::logException($exception);

        // 非用户级异常短信告警
        if (YII_ENV_PROD && !$exception instanceof UserException) {
            $name = ($exception instanceof Exception || $exception instanceof ErrorException) ? $exception->getName() : 'Exception';
            $server_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
            $message = $name . ' 发生异常' . $server_ip . '：' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
//            Yii::error("：".$message);
        }
    }
}