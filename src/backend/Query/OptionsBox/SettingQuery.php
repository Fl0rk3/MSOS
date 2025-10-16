<?php

declare(strict_types=1);

namespace MSOS\backend\Query\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Entity\User;
use MSOS\backend\Entity\UserSetting;
use MSOS\backend\Provider\Links\DoctrineLinksProvider;
use MSOS\backend\Repository\UserSettingRepository;

class SettingQuery
{
    private DoctrineLinksProvider $doctrineLinksProvider;

    function __construct(
        private EntityManagerInterface $em,
    )
    {
        $this->doctrineLinksProvider = new DoctrineLinksProvider($this->em);
    }

    /**
     * @param array<string, string> $settings_values
     * @throws ORMException
     */
    public function updateSettings(int $user_id, array $settings_values): string
    {
        $userRef = $this->em->getReference(User::class, $user_id);
        if ($userRef === null) {
            return 'false';
        }

        /** @var UserSettingRepository $repo */
        $repo = $this->em->getRepository(UserSetting::class);
        $user_settings = $repo->findAllForUser($userRef);
        if ($user_settings === null) {
            return 'false';
        }

        $is_settings_changed = false;

        foreach ($user_settings as $user_setting) {
            if ($user_setting->getSettingValue() !== $settings_values[$user_setting->getSettingName()]) {
                $user_setting->setSettingValue($settings_values[$user_setting->getSettingName()]);
                $this->em->persist($user_setting);
                $is_settings_changed = true;
            }
        }

        if ($is_settings_changed) {
            $this->em->flush();
            return 'true';
        }

        return 'no_change';
    }

}