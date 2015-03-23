<?php namespace App\Src\Instagram;

use App\Core\BaseRepository;

class InstagramRepository extends BaseRepository
{
    public $model;
    /**
     * @param Instagram $model
     */
    public function __construct(Instagram $model)
    {
        $this->model = $model;
    }

}
