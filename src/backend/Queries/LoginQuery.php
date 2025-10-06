<?php

namespace MSOS\backend\Queries;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Entity\User;

class LoginQuery
{
    function __construct(
        private EntityManagerInterface $em,
    )
    {
    }

    public function isUserExist(string $username): bool
    {
        return $this->em->getRepository(User::class)->findOneBy(['username' => $username]) !== null;
    }

    /**
     * @return array{user_id:int, username:string, is_admin:int}|null
     */
    public function loginUser(string $username, string $password): ?array
    {
        $repo = $this->em->getRepository(User::class);
        /** @var User|null $user */
        $user = $repo->findOneBy(['username' => $username]);

        if ($user === null) {
            return null;
        }

        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        return [
            'user_id' => $user->getUserId(),
            'username' => $user->getUsername(),
            'is_admin' => $user->getIsAdmin(),
        ];
    }
}
