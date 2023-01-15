<?php

declare(strict_types=1);

namespace Alura\Mvc\Util;
use Alura\Mvc\Entity\Video;

class Upload
{
  public function doUploadFile(Video $video): void 
  {
    if ($_FILES['image'] ['error'] === UPLOAD_ERR_OK) {
 
      move_uploaded_file(
              $_FILES['image'] ['tmp_name'],
              __DIR__ . '/../../public/img/uploads/' . $_FILES['image'] ['name']
      );
      $video->setFilePath($_FILES['image'] ['name']);
    }   
  }
}