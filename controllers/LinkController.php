<?php

namespace app\controllers;

use app\models\Link;
use Exception;
use Yii;
use yii\helpers\Url;
use yii\rest\Controller;

class LinkController extends Controller {
    public $modelClass = Link::class;
    public $enableCsrfValidation = false;
    
    public function actionView($hash) {
        if (empty($link = Link::findOne(['hash' => $hash]))) {
            return $this->errorResponse('URL not found by given hash');   
        }
        
        return $this->asJson([
            'url' => $link->target,
            'use_count' => $link->use_count
        ]);
    }
    
    public function actionCreate() {
        $url = Yii::$app->request->post('url');
        
        $link = new Link([
            'target' => $url
        ]);
        
        if (!$link->save()) {
            return $this->errorResponse('Error during creating of short URL: ');
        }
        
        return $this->asJson([
            'url' => Url::to($link->hash, true),
        ]);        
    }
    
    private function errorResponse($msg) {
        Yii::$app->response->statusCode = 400;
        
        return $this->asJson(['error' => $msg]);
    }
}