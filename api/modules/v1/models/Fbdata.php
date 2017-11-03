<?php

namespace api\modules\v1\models;

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
}
