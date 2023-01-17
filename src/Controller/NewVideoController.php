<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Util\Upload;
use Alura\Mvc\Helper\FlashMessageTrait;

class NewVideoController implements Controller
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function processaRequisicao(): void
  {
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    if ($url === false) {
      $this->addErrorMessage('URL inválida');
      header('Location: /novo-video');
      return;
    }
    $titulo = filter_input(INPUT_POST, 'titulo');
    if ($titulo === false) {
      $this->addErrorMessage('Título não informado');
      header('Location: /novo-video');
      return;
    }

    $video = new Video($url, $titulo);

    $upload = new Upload();
    $upload->doUploadFile($video);

    $success = $this->videoRepository->add($video);
    if ($success === false) {
      $this->addErrorMessage('Erro ao cadastrar vídeo');
      header('Location: /novo-video');
    } else {
      header('Location: /');
    }
  }
}