<?php
namespace App\Core;

use Exception;
use Illuminate\Support\MessageBag;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

abstract class BaseImageService extends BaseRepository
{

    private $hashedName;

    private $uploadDir;

    private $thumbnailImagePath;

    private $largeImagePath;

    private $mediumImagePath;

    protected $largeImageWidth = '750';

    protected $largeImageHeight = '700';

    protected $mediumImageWidth = '500';

    protected $mediumImageHeight = '450';

    protected $thumbnailImageWidth = '250';

    protected $thumbnailImageHeight = '200';

    public function __construct(MessageBag $errors)
    {
        $this->uploadDir          = public_path() . '/uploads/';
        $this->largeImagePath     = $this->getUploadDir() . 'large/';
        $this->mediumImagePath    = $this->getUploadDir() . 'medium/';
        $this->thumbnailImagePath = $this->getUploadDir() . 'thumbnail/';
        $this->messageBag         = $errors;
    }

    abstract function store(UploadedFile $image);

    protected function process(UploadedFile $image, array $imageDimensions = [])
    {
        $this->setHashedName($image);

        try {
            foreach ($imageDimensions as $imageDimension) {
                switch ($imageDimension) {
                    case 'large':
                        Image::make($image->getRealPath())->resize($this->largeImageWidth,
                            $this->largeImageHeight)->save($this->largeImagePath . $this->hashedName);
                        break;
                    case 'medium':
                        Image::make($image->getRealPath())->resize($this->mediumImageWidth,
                            $this->mediumImageHeight)->save($this->mediumImagePath . $this->hashedName);
                        break;
                    case 'thumbnail':
                        Image::make($image->getRealPath())->resize($this->thumbnailImageWidth,
                            $this->thumbnailImageHeight)->save($this->thumbnailImagePath . $this->hashedName);
                        break;
                    default :
                        break;
                }
            }
        } catch ( Exception $e ) {
            dd($e->getMessage());
            $this->addError($e->getMessage());

            return false;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * @return mixed
     */
    public function getLargeImagePath()
    {
        return $this->largeImagePath;
    }

    /**
     * @return mixed
     */
    public function getMediumImagePath()
    {
        return $this->mediumImagePath;
    }

    /**
     * @return mixed
     */
    public function getThumbnailImagePath()
    {
        return $this->thumbnailImagePath;
    }

    private function setHashedName($image)
    {
        $this->hashedName = md5(uniqid(rand() * (time()))) . '.' . $image->getClientOriginalExtension();
    }

    public function getHashedName()
    {
        return $this->hashedName;
    }

    public function destroy($name)
    {
        if (file_exists($this->getThumbnailImagePath() . $name)) {
            unlink($this->getThumbnailImagePath() . $name);
        }
        if (file_exists($this->getMediumImagePath() . $name)) {
            unlink($this->getMediumImagePath() . $name);
        }
        if (file_exists($this->getLargeImagePath() . $name)) {
            unlink($this->getLargeImagePath() . $name);
        }
    }

}