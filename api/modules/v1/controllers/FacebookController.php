<?php

namespace api\modules\v1\controllers;

use common\models\Fbdata;
use yii\rest\ActiveController;
use common\components\Facebook;

/**
 * Facebook Controller API
 */
class FacebookController extends ActiveController
{

    public $modelClass = 'api\modules\v1\models\Fbdata';

    public function actions()
    {
        $actions = parent::actions();
        // disable the "delete", "create", "update", "view" and "options" actions
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['view'], $actions['options']);
        return $actions;
    }

    /**
     * @return array|null
     */
    public function actionGetPageIdByUrl()
    {
        $url = \Yii::$app->request->post('url');
        if (!empty($url)) {
            $fb = new Facebook(\Yii::$app->params['api_facebook']);
            return $fb->GetPageIdByUrl($url);
        }
        return null;
    }


    /**
     * @param $page_id
     * @param $live 1 or 0
     * @return array
     */
    public function actionGetPageDataByPageId($page_id, $live)
    {
        if ($live) {
            $posts = [];
            $page_data = [];
            $fb = new Facebook(\Yii::$app->params['api_facebook']);
            $posts_o = $fb->GetPagePostsByPageId($page_id);
            if (!empty($posts_o)) {
                $posts = $posts_o->getBody();
                $posts = json_decode($posts, true);
                $page_data_o = $fb->getPageDataByPageId($page_id);
                if (!empty($posts_o)) {
                    $page_data = $page_data_o->getDecodedBody();
                    Fbdata::SavePageData($page_id, $page_data, $posts);
                }
            }
            return [
                'db' => false,
                'posts' => $posts,
                'page_data' => $page_data,
            ];
        } else {
            $posts = [];
            $page_data = [];
            $fb_data = Fbdata::GetFbDataByPageId($page_id);
            if (empty($fb_data)) {
                $fb = new Facebook(\Yii::$app->params['api_facebook']);
                $posts_o = $fb->GetPagePostsByPageId($page_id);
                if (!empty($posts_o)) {
                    $posts = $posts_o->getBody();
                    $posts = json_decode($posts, true);
                    $page_data_o = $fb->getPageDataByPageId($page_id);
                    if (!empty($page_data_o)) {
                        $page_data = $page_data_o->getDecodedBody();
                        Fbdata::SavePageData($page_id, $page_data, $posts);
                    }
                }
                return [
                    'db' => false,
                    'posts' => $posts,
                    'page_data' => $page_data,
                ];
            }
        }
        return [
            'db' => true,
            'posts' => $fb_data['posts'],
            'page_data' => $fb_data['data'],
        ];
    }

    public function actionCrone()
    {
        Fbdata::CronRun();
    }
}