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
      $_SESSION['error_message'] = 'URL inválida';
      header('Location: /novo-video');
      return;
    }
    $titulo = filter_input(INPUT_POST, 'titulo');
    if ($titulo === false) {
      $_SESSION['error_message'] = 'Título não informado';
      header('Location: /novo-video');
      return;
    }

    $video = new Video($url, $titulo);

    $upload = new Upload();
    $upload->doUploadFile($video);

    $success = $this->videoRepository->add($video);
    if ($success === false) {
      $_SESSION['error_message'] = 'Erro ao cadastrar vídeo';
      header('Location: /novo-video');
    } else {
      header('Location: /');
    }
  }
}