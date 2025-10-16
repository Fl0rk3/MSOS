<?php

namespace MSOS\backend\Query;

use Doctrine\ORM\EntityManagerInterface;
use MSOS\backend\Constants\SettingConstants;
use MSOS\backend\ctrl\UserSettingDefault;
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

        $user_settings = UserSettingDefault::get();
        $user_settings = $user_settings->getAllDefaultValues();

        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_SYSTEM_LANGUAGE, $user_settings[SettingConstants::SETTING_SYSTEM_LANGUAGE]));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_TIME_FORMAT, $user_settings[SettingConstants::SETTING_TIME_FORMAT]));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_SYSTEM_COLOR_MODE, $user_settings[SettingConstants::SETTING_SYSTEM_COLOR_MODE]));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_DAY_START_HOUR, $user_settings[SettingConstants::SETTING_DAY_START_HOUR]));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_DAY_END_HOUR, $user_settings[SettingConstants::SETTING_DAY_END_HOUR]));
        $this->em->persist(new UserSetting($user, SettingConstants::SETTING_FIRST_WEEK_DAY, $user_settings[SettingConstants::SETTING_FIRST_WEEK_DAY]));
        $this->em->flush();


        return [
            'user_id' => $user->getUserId(),
            'username' => $user->getUsername(),
            'is_admin' => $user->getIsAdmin(),
        ];
    }
}
