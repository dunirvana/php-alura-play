<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;
use Alura\Mvc\Entity\User;
use Alura\Mvc\Repository\UserRepository;
use Alura\Mvc\Helper\FlashMessageTrait;

class LoginController implements Controller
{
    use FlashMessageTrait;

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $user = new User(
            filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
            filter_input(INPUT_POST, 'password')
        );

        $correctPassword = $this->userRepository->userIsValid($user);
        
        if ($correctPassword) {
            $_SESSION['logado'] = true;
            header('Location: /');
        } else {
            $this->addErrorMessage('Usuário ou senha inválidos');
            header('Location: /login');
        }
    }
}