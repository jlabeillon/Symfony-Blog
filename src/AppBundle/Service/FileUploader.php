<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function delete($filename)
    {
        // Chemin complet vers le fichier
        $toDelete = $this->getTargetDir().'/'.$filename;
        // Si le fichier existe
        if(is_file($toDelete)) {
            unlink($toDelete);
        }
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}
