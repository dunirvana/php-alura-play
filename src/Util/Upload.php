<?php

declare(strict_types=1);

namespace Alura\Mvc\Util;
use Alura\Mvc\Entity\Video;
use finfo;

class Upload
{
  public function doUploadFile(Video $video): void 
  {
    // TODO: criar processo de slug para o nome do arquivo
    if ($_FILES['image'] ['error'] === UPLOAD_ERR_OK) {
      $safeFileName = uniqid('upload_') . '_' . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME);
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $mimeType = $finfo->file($_FILES['image']['tmp_name']);

      if(str_starts_with($mimeType, 'image/')){
        move_uploaded_file(
          $_FILES['image'] ['tmp_name'],
          __DIR__ . '/../../public/img/uploads/' . $safeFileName);
        $video->setFilePath($safeFileName);
      }
    }   
  }
}