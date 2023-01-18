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
use Psr\Http\Server\RequestHandlerInterface;

class NewVideoController implements RequestHandlerInterface
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function handle(ServerRequestInterface $request): ResponseInterface
  {
    $requestBody = $request->getParsedBody();

    $url = filter_var($requestBody['url'], FILTER_VALIDATE_URL);
    if ($url === false) {
      $this->addErrorMessage('URL inválida');
      return new Response(302, [
        'Location' => '/novo-video'
      ]);
    }

    $titulo = filter_var($requestBody['titulo']);
    if ($titulo === false) {
      $this->addErrorMessage('Título não informado');
      return new Response(302, [
        'Location' => '/novo-video'
      ]);
    }

    $video = new Video($url, $titulo);
    $files = $request->getUploadedFiles();

    $upload = new Upload();
    $upload->doUploadFile($video, $files);

    $success = $this->videoRepository->add($video);
    if ($success === false) {
      $this->addErrorMessage('Erro ao cadastrar vídeo');
      return new Response(302, [
        'Location' => '/novo-video'
      ]);
    } else {
      return new Response(302, [
        'Location' => '/'
      ]);
    }
  }
}