<?php

namespace MSOS\backend\Queries;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Entity\User;

class RegisterQuery
{
    function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @return array{user_id:int|null, username:string, is_admin:int}|null
     */
    public function registerUser(string $username, string $password): array|null
    {
        $username = trim($username);

        $repo = $this->em->getRepository(User::class);
        if ($repo->findOneBy(['username' => $username])) {
            return null;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $user = new User($username);
        $user->setPassword($hash);
        $user->setIsAdmin(0);

        $this->em->persist($user);
        $this->em->flush();


        return [
            'user_id' => $user->getUserId(),
            'username' => $user->getUsername(),
            'is_admin' => $user->getIsAdmin(),
        ];
    }
}
