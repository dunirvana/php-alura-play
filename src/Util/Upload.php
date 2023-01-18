<?php

declare(strict_types=1);

namespace Alura\Mvc\Util;
use Alura\Mvc\Entity\Video;
use finfo;

class Upload
{
  public function doUploadFile(Video $video, array $files): void 
  {
    $uploadedImage = $files['image'];
    if ($uploadedImage->getError() === UPLOAD_ERR_OK) {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $tmpFile = $uploadedImage->getStream()->getMetadata('uri');
        $mimeType = $finfo->file($tmpFile);

        if (str_starts_with($mimeType, 'image/')) {
            $safeFileName = $this->slugify(uniqid('upload_') . '_' . pathinfo($uploadedImage->getClientFilename(), PATHINFO_BASENAME));
            $uploadedImage->moveTo(__DIR__ . '/../../public/img/uploads/' . $safeFileName);
            $video->setFilePath($safeFileName);
        }
    }
        
    // if ($_FILES['image'] ['error'] === UPLOAD_ERR_OK) {
    //   $safeFileName = $this->slugify(uniqid('upload_') . '_' . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME));
    //   $finfo = new finfo(FILEINFO_MIME_TYPE);
    //   $mimeType = $finfo->file($_FILES['image']['tmp_name']);

    //   if(str_starts_with($mimeType, 'image/')){
    //     move_uploaded_file(
    //       $_FILES['image'] ['tmp_name'],
    //       __DIR__ . '/../../public/img/uploads/' . $safeFileName);
    //     $video->setFilePath($safeFileName);
    //   }
    // }   
  }

  public function slugify($text, string $divider = '-'): string
  {
    // substituir caracteres que não são letras ou digitos po um divisor
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
  
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  
    // remove caracteres indesejados
    $text = preg_replace('~[^-\w]+~', '', $text);
  
    // trim
    $text = trim($text, $divider);
  
    // remove divisores duplicados
    $text = preg_replace('~-+~', $divider, $text);
  
    // lowercase
    $text = strtolower($text);
  
    if (empty($text)) {
      return 'n-a';
    }
  
    return $text;
  }  
}