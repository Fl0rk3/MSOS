<?php

declare(strict_types=1);

namespace MSOS\backend\Provider\Settings;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Entity\User;
use MSOS\backend\Entity\UserSetting;
use MSOS\backend\Repository\UserSettingRepository;

class DoctrineSettingsProvider implements SettingsProvider
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @throws ORMException
     */
    public function forUser(int $user_id): array
    {
        $user_ref = $this->em->getReference(User::class, $user_id);
        /** @var UserSettingRepository $repo */
        $repo = $this->em->getRepository(UserSetting::class);
        $user_settings = $repo->findAllForUser($user_ref);
        return array_column(
            array_map(
                fn(UserSetting $l) => [
                    'settingName' => $l->getSettingName(),
                    'settingValue' => $l->getSettingValue()
                ],
                $user_settings
            ),
            'settingValue',
            'settingName'
        );
    }
}