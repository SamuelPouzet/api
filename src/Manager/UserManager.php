<?php

namespace SamuelPouzet\Api\Manager;

use stdClass;

class UserManager
{

    public function __construct(
        protected \PDO $connexion
    ) {
    }

    public function getByUser(string $login): false|array
    {
        $sql = <<<'SQL'
SELECT * 
FROM user u
WHERE login = :login;
SQL;
        $stmt = $this->connexion->prepare($sql);
        $stmt->bindValue('login', $login);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}