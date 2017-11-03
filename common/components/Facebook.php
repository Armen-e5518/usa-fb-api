<?php
namespace common\components;

class Facebook
{

    public $facebook_ob;

    public function __construct($config)
    {
        $this->facebook_ob = new \Facebook\Facebook($config);
    }

    /*
     * Get page id by page url
     */
    public function getPageIdByUrl($url = null)
    {
        try {
            $response = $this->facebook_ob->get($url);
            if ($response->getHttpStatusCode() == 200) {
                return $response->getDecodedBody();
            }
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return null;
    }

    /*
     * Get page posts by page id
     */
    public function getPagePostsByPageId($id = null)
    {
        try {
            return $this->facebook_ob->get('/' . $id . '/posts?fields=message,created_time,attachments.limit(1)');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }


    /*
     * Get post data by post id
     */
    public function getPostDataByPostId($id = null)
    {
        try {
            return $this->facebook_ob->get('/' . $id . '?fields=attachments');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }

    /*
       * Get page data by page id
       */
    public function getPageDataByPageId($id = null)
    {
        try {
            return $this->facebook_ob->get($id . '?fields=phone,photos.limit(1){picture},description,cover');
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    }
}