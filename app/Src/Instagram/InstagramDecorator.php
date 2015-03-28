<?php namespace App\Src\Instagram;

use MetzWeb\Instagram\Instagram as BaseInstagram;
use Vinkla\Instagram\InstagramManager;

class InstagramDecorator extends BaseInstagram
{
    public $instagramManager;

    public function __construct(InstagramManager $instagramManager)
    {
        $this->instagramManager = $instagramManager;
    }

    public function getInstas(){
        dd($this->instagramManager->getUserMedia('1097866395'));
    }

    public function likePagination($obj, $limit = 0) {
        if (true === is_object($obj) && !is_null($obj->pagination)) {
            if (!isset($obj->pagination->next_url)) {
                return;
            }
            $apiCall = explode('?', $obj->pagination->next_url);
            if (count($apiCall) < 2) {
                return;
            }
            $function = str_replace(self::API_URL, '', $apiCall[0]);
            $auth = (strpos($apiCall[1], 'access_token') !== false);
            if (isset($obj->pagination->next_max_like_id)) {
                return $this->_makeCall($function, $auth, array('max_id' => $obj->pagination->next_max_like_id, 'count' => $limit));
            } else {
                return $this->_makeCall($function, $auth, array('cursor' => $obj->pagination->next_cursor, 'count' => $limit));
            }
        } else {
            throw new \Exception("Error: pagination() | This method doesn't support pagination.");
        }
    }


}
