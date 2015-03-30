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

}
