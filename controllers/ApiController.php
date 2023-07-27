<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class ApiController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
			'corsFilter' => [
				'class' => Cors::class,
				'cors' => [
					'Origin' => ['*'],
					'Access-Control-Request-Method' => ['POST', 'OPTIONS'],
					'Access-Control-Request-Headers' => ['Content-Type'],
				],
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'*' => ['post','options'],
				],
			],
			'authenticator' => [
				'class' => HttpBearerAuth::class,
                // 'except' => [
                //     '*',
                // ],
                // 'tokenParam' => 'auth_key',
			],
			'contentNegotiator'=>[
				'class' => ContentNegotiator::class, 
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_JSON,
					'text/html' => Response::FORMAT_JSON,
					'text/xml' => Response::FORMAT_JSON,
				],
			],
		]);
    }
    public function init()
    {
        Yii::$app->request->enableCsrfValidation = false; 
        Yii::$app->user->enableSession = false;
		Yii::$app->user->loginUrl      = null;

		// Before sending response we'll reformat it
		Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
			$response = $event->sender;

			// If our response is not empty
			if ($response->data !== null) {
				// Reformat for failed response
				if ($response->isServerError || $response->isClientError) {
					$data      = $response->data;
					$exception = Yii::$app->errorHandler->exception;
					$code      = $exception->getCode();
					$message   = $exception->getMessage();
					$response->statusCode = 200; // Always set response statusCode to 200

					if ($exception instanceof ForbiddenHttpException) {
						$response->statusCode = 403;
					}

					// Wrap response with 'error' key
					$response->data = [
						'status' => false,
						'error' => [
							'status_code' => $code,
							'message' => $message
						],
					];

				// Reformat for success response
				} else {
					// Wrap response with 'data' key
					$response->data = array_merge([
						'status' => true,
						'data' => $response->data,
					]);
				}
			}
		});

		Yii::$app->response->format = Response::FORMAT_JSON;
		return parent::init();
    }

}
