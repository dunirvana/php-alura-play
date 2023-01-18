<?php
declare(strict_types=1);
namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\User;
use Alura\Mvc\Helper\FlashMessageTrait;
use Alura\Mvc\Repository\UserRepository;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        $email = filter_var($requestBody['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            $this->addErrorMessage('Usuário ou senha inválidos');
            return new Response(302, ['Location' => '/login']);$this->addErrorMessage('Usuário ou senha inválidos');
        }

        $user = new User(
            $email,
            filter_var($requestBody['password'])
        );

        $correctPassword = $this->userRepository->userIsValid($user);


        if (!$correctPassword) {
            $this->addErrorMessage('Usuário ou senha inválidos');
            return new Response(302, ['Location' => '/login']);$this->addErrorMessage('Usuário ou senha inválidos');
        }

        $_SESSION['logado'] = true;
        return new Response(302, ['Location' => '/']);
    }
}