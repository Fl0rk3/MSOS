<?php

declare(strict_types=1);

namespace MSOS\backend\Repository;

use Doctrine\ORM\EntityRepository;
use MSOS\backend\ctrl\UserSettingDefault;
use MSOS\backend\Entity\User;
use MSOS\backend\Entity\UserSetting;

class UserSettingRepository extends EntityRepository
{
    /**
     * @return UserSetting[]
     */
    public function findAllForUser(?User $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function findSettingValue(User $user, string $setting): ?string
    {
        /** @var UserSetting|null $setting_data */
        $setting_data = $this->find([
            'user_id' => $user,
            'setting' => $setting
        ]);
        if ($setting_data === null) {
            $default_object = UserSettingDefault::get();
            $value = $default_object->getDefaultValue($setting);
        } else {
            $value = $setting_data->getSettingValue();
        }

        return $value;
    }
}