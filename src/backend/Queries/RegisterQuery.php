<?php

namespace MSOS\backend\Queries;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Constants\SettingConstants;
use MSOS\backend\Entity\User;
use MSOS\backend\Entity\UserSetting;

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
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_SYSTEM_LANGUAGE, 'en'));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_SYSTEM_COLOR_MODE, 'dark'));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_DAY_START_HOUR, '7'));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_DAY_END_HOUR, '21'));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_FIRST_WEEK_DAY, 'Monday'));
        $this->em->flush();


        return [
            'user_id' => $user->getUserId(),
            'username' => $user->getUsername(),
            'is_admin' => $user->getIsAdmin(),
        ];
    }
}
