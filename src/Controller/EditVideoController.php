<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Util\Upload;
use Alura\Mvc\Helper\FlashMessageTrait;

class EditVideoController implements Controller
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function processaRequisicao(): void
  {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false || $id === null) {
      $this->addErrorMessage('ID obrigatório');
      header('Location: /editar-video');
      return;
    }

    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    if ($url === false) {
      $this->addErrorMessage('URL inválida');
      header('Location: /editar-video');
      return;
    }
    $titulo = filter_input(INPUT_POST, 'titulo');
    if ($titulo === false) {
      $this->addErrorMessage('Título não informado');
      header('Location: /editar-video');
      return;
    }
    
    $video = new Video($url, $titulo);
    $video->setId($id);

    $upload = new Upload();
    $upload->doUploadFile($video);

    $success = $this->videoRepository->update($video);

    if ($success === false) {
      $this->addErrorMessage('Erro ao atualizar vídeo');
      header('Location: /editar-video');
    } else {
      header('Location: /');
    }
  }
}