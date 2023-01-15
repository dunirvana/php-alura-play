<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Util\Upload;

class NewVideoController implements Controller
{
  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function processaRequisicao(): void
  {
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    if ($url === false) {
      header('Location: /?sucesso=0');
      return;
    }
    $titulo = filter_input(INPUT_POST, 'titulo');
    if ($titulo === false) {
      header('Location: /?sucesso=0');
      return;
    }

    $video = new Video($url, $titulo);

    $upload = new Upload();
    $upload->doUploadFile($video);

    $success = $this->videoRepository->add($video);
    if ($success === false) {
      header('Location: /?sucesso=0');
    } else {
      header('Location: /?sucesso=1');
    }
  }
}