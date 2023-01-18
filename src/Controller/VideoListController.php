<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Repository\VideoRepository;
use Alura\Mvc\Helper\FlashMessageTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VideoListController extends ControllerWithHtml
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function processaRequisicao(ServerRequestInterface $request): ResponseInterface
  {
    $videoList = $this->videoRepository->all();
    return new Response(200, body: $this->renderTemplate( 'video-list', ['videoList' => $videoList] ));
  }
}