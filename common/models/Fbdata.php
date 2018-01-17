<?php

namespace common\models;

use common\components\Facebook;
use Yii;

/**
 * This is the model class for collection "Fbdata".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $page_id
 * @property mixed $data
 * @property mixed $posts
 */
class Fbdata extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['policearrests', 'Fbdata'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'page_id',
            'data',
            'posts',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'data', 'posts'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'page_id' => 'Page ID',
            'data' => 'Data',
            'posts' => 'Posts',
        ];
    }

    /*
  * Get Page data by page id
  */
    public static function GetFbDataByPageId($page_id = null)
    {
        if (!empty($page_id)) {
            return self::findOne(['page_id' => $page_id]);
        }
        return [];
    }

    /**
     * Save Facebook Page Data in DB
     * @param null $page_id
     * @param null $page_data
     * @param null $posts
     * @return bool
     */
    public static function SavePageData($page_id = null, $page_data = null, $posts = null)
    {
        foreach ($posts['data'] as $kay => $data) {
            if (!empty($data['attachments']['data'][0]['media']['image']['src'])) {
                $posts['data'][$kay]['attachments']['data'][0]['media']['image']['src_loc'] = self::SaveImage($data['id'], $data['attachments']['data'][0]['media']['image']['src']);
            } elseif (!empty($data['attachments']['data'][0]['subattachments']['data'][0]['media']['image']['src'])) {
                $posts['data'][$kay]['attachments']['data'][0]['subattachments']['data'][0]['media']['image']['src_loc'] = self::SaveImage($data['id'], $data['attachments']['data'][0]['subattachments']['data'][0]['media']['image']['src']);
            }
        }
        if (!empty($page_id)) {
            $model = self::findOne(['page_id' => $page_id]);
            if (empty($model)) {
                $model_new = new self();
                $model_new->page_id = $page_id;
                $model_new->data = $page_data;
                $model_new->posts = $posts;
                return $model_new->save();
            } else {
                $model->data = $page_data;
                $model->posts = $posts;
                return $model->save();
            }
        }
        return false;
    }


    /**
     * @param $url
     * @return null|string
     */
    public static function SaveImage($id = null, $url = null)
    {
        if (!empty($id) && !empty($url)) {
            $file_name = $id . '.jpg';
            $file_path = Yii::getAlias('@api') . '/web/images/' . $file_name;
            if (!file_exists($file_path)) {
                if (!empty(getimagesize($url))) {
                    if (fopen($file_path, 'w') && !empty($url) && file_put_contents($file_path, file_get_contents($url))) {
                        return $file_name;
                    }
                }
            } else {
                return $file_name;
            }
        }
        return null;
    }

    /**
     * Crone update fb posts
     */
    public static function CronRun()
    {
        $offset = (int)file_get_contents('crone_limit.txt');
        $limit = 150;
        $count = self::find()->count();
        $model = self::find()
            ->select(['page_id'])
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();
        $d = 0;
        foreach ($model as $kay => $m) {
            $fb = new Facebook(\Yii::$app->params['api_facebook']);
            $posts_o = $fb->GetPagePostsByPageId($m['page_id']);
            if (!empty($posts_o)) {
                $posts = $posts_o->getBody();
                $posts = json_decode($posts, true);
                $page_data_o = $fb->getPageDataByPageId($m['page_id']);
                if (!empty($page_data_o)) {
                    $page_data = $page_data_o->getDecodedBody();
                    Fbdata::SavePageData($m['page_id'], $page_data, $posts);
                    $d = $kay + 1;
                }
            }
        }
        if ($offset + $limit >= $count) {
            file_put_contents('crone_limit.txt', 0);
        } else {
            file_put_contents('crone_limit.txt', $offset + $limit);
        }
        echo 'Updated offset ' . $offset . ' limit ' . $limit . ' All-' . ($d) . ' Fb pages';
    }

    /**
     *
     */
    public static function AllCronRun()
    {
        $model = self::find()->select(['page_id'])->asArray()->all();
        $d = 0;
        foreach ($model as $kay => $m) {
            $fb = new Facebook(\Yii::$app->params['api_facebook']);
            $posts_o = $fb->GetPagePostsByPageId($m['page_id']);
            if (!empty($posts)) {
                $posts = $posts_o->getBody();
                $posts = json_decode($posts, true);
                $page_data_o = $fb->getPageDataByPageId($m['page_id']);
                if (!empty($page_data_o)) {
                    $page_data = $page_data_o->getDecodedBody();
                    Fbdata::SavePageData($m['page_id'], $page_data, $posts);
                    $d = $kay;
                }
            }
        }
        echo 'Updated ' . ($d + 1) . ' Fb pages';
    }
}
