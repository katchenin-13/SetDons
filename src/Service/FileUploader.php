<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct(?string $targetDirectory = null)
    {
        $this->setTargetDirectory($targetDirectory);
    }

    public function upload(UploadedFile $file, $prefix = null, &$path = null, $newFileName = false, $replacePath = false)
    {
        if (!$replacePath) {
            if ($prefix == 'private') {
                $path = dirname($this->targetDirectory).'/data';
            } else {
                $path = $this->targetDirectory.'/public/uploads/nas';
            }
        }

       


        $this->setTargetDirectory($path);

        $extension = $file->guessExtension();

        if (!$extension) {
            $extension = $file->getClientOriginalExtension();
        }

        $realFileName = str_slug(basename($file->getClientOriginalName(), ".{$extension}"), '_');

       

        $fileName = $newFileName === false ? md5(uniqid()) : (substr($newFileName.$realFileName, 0, 200));
        $fileName .= '.'.$extension;

    
        $file->move($this->getTargetDirectory(), $fileName);


        $path .= "/{$fileName}";

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
}