<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\VideoRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RemoveVideoCoverController implements RequestHandlerInterface
{
  use FlashMessageTrait;

  public function __construct(private VideoRepository $videoRepository)
  {
  }

  public function handle(ServerRequestInterface $request): ResponseInterface
  {
    // TODO: criar processo para remover imagens sem refrência
    $queryParams = $request->getQueryParams();

    $id = filter_var($queryParams['id'], FILTER_VALIDATE_INT);
    if ($id === null || $id === false) {
      $this->addErrorMessage('ID inválido');
      return new Response(302, [
        'Location' => '/'
      ]);
    }

    $success = $this->videoRepository->removeCover($id);
    if ($success === false) {
      $this->addErrorMessage('Erro ao remover capa');
      return new Response(302, [
        'Location' => '/'
      ]);
    } else {      
      return new Response(302, [        
        'Location' => '/'
      ]);
    }

  }
}