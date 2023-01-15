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

    if (password_needs_rehash($userData['password'], PASSWORD_ARGON2ID)) {
      // caso o algoritmo de criptografia mude podemos atualizar os usuários dessa forma para o novo algoritmo, alterando o 'PASSWORD_ARGON2ID' pelo novo
      $statement = $this->pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
      $statement->bindValue(1, password_hash($user->getPassword(), PASSWORD_ARGON2ID));
      $statement->bindValue(2, $userData['id']);
      $statement->execute();
    }    

    return $correctPassword;
  }

}