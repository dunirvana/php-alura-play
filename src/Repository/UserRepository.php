<?php

declare(strict_types=1);

namespace Alura\Mvc\Repository;

use Alura\Mvc\Entity\User;
use PDO;


class UserRepository
{
  public function __construct(private PDO $pdo)
  {
  }

  public function userIsValid(User $user)
  {
    $sql = 'SELECT * FROM users WHERE email = ?';
    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(1, $user->getEmail());
    $statement->execute();

    $userData = $statement->fetch(\PDO::FETCH_ASSOC);
    $correctPassword = password_verify($user->getPassword(), $userData['password'] ?? '');

    return $correctPassword;
  }

}