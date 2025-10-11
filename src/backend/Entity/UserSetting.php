<?php

declare(strict_types=1);

namespace MSOS\backend\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_settings', schema: 'msos')]
class UserSetting
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(
        name: 'user_id',
        referencedColumnName: 'user_id',
        nullable: false,
        onDelete: 'CASCADE'
    )]
    private User $user;

    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    private string $setting_name;

    #[ORM\Column(type: Types::STRING)]
    private string $setting_value;

    public function __construct(User $user, string $setting_name, string $setting_value)
    {
        $this->user = $user;
        $this->setting_name = $setting_name;
        $this->setting_value = $setting_value;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSettingName(): string
    {
        return $this->setting_name;
    }

    public function getSettingValue(): string
    {
        return $this->setting_value;
    }

    public function setSettingValue(string $setting_value): void
    {
        $this->setting_value = $setting_value;
    }

}