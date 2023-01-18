<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Util\Upload;
use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EditVideoController implements Controller
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
  {
    $queryParams = $request->getQueryParams();
    $requestBody = $request->getParsedBody();

    $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
    if ($id === false || $id === null) {
      $this->addErrorMessage('ID inválido');
      return new Response(302, [
          'Location' => '/editar-video?id=' . $id
      ]);
    }

    $url = filter_var($requestBody['url'], FILTER_VALIDATE_URL);
    if ($url === false) {
      $this->addErrorMessage('URL inválida');
      return new Response(302, [
        'Location' => '/editar-video?id=' . $id
      ]);
    }

    $titulo = filter_var($requestBody['titulo']);
    if ($titulo === false) {
      $this->addErrorMessage('Título não informado');
      return new Response(302, [
        'Location' => '/editar-video?id=' . $id
      ]);
    }
    
    $video = new Video($url, $titulo);
    $video->setId($id);
    $files = $request->getUploadedFiles();

    $upload = new Upload();
    $upload->doUploadFile($video, $files);

    $success = $this->videoRepository->update($video);

    if ($success === false) {
      $this->addErrorMessage('Erro ao atualizar vídeo');
      return new Response(302, [
        'Location' => '/editar-video?id=' . $id
      ]);
    } else {
      return new Response(302, [
        'Location' => '/'
      ]);
    }
  }
}