<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureSerivce
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, string $folder, ?int $width = 250, ?int $height = 250)
    {
        // Rename the file
        $pictureName = md5(uniqid(rand(), true)) . '.webp';

        // Get file info
        $pictureInfo = getimagesize($picture);

        if ($pictureInfo === false) {
            throw new \Exception('Format d\'image non supporté');
        }

        // Check file format
        switch ($pictureInfo['mime']) {
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture);
                break;
            default:
                throw new \Exception('Format d\'image non supporté');
        }

        // Resize the picture
        $pictureWidth = $pictureInfo[0];
        $pictureHeight = $pictureInfo[1];

        // Check orientation of the picture
        switch ($pictureWidth <=> $pictureHeight) {
            case -1: // portrait
                $squareSize = $pictureWidth;
                $srcX = 0;
                $srcY = ($pictureHeight - $squareSize) / 2;
                break;
            case 0: // square
                $squareSize = $pictureWidth;
                $srcX = 0;
                $srcY = 0;
                break;
            case 1: // landscape
                $squareSize = $pictureHeight;
                $srcX = ($pictureWidth - $squareSize) / 2;
                $srcY = 0;
                break;
        }

        // Create a new picture
        $resizedPicture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $srcX, $srcY, $width, $height, $squareSize, $squareSize);

        $path = $this->params->get('pictures_directory') . '/' . $folder;

        // Create folder destination if not exists
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Save the picture
        imagewebp($resizedPicture, $path . '/' . $width . 'x' . $height . '-' . $pictureName, 80);
        $picture->move($path . '/' . $pictureName);

        return $pictureName;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        if ($file !== 'default.webp') {
            $success = false;
            $path = $this->params->get('pictures_directory') . '/' . $folder;

            $picture = $path . '/' . $width . 'x' . $height . '-' . $file;
            if (file_exists($picture)) {
                unlink($picture);
                $success = true;
            }

            return $success;
        }

        return false;
    }
}
