<?php

declare(strict_types=1);

namespace MSOS\backend\ctrl;

use MSOS\backend\Constants\SettingConstants;

class UserSettingDefault
{
    /**
     * @var UserSettingDefault
     */
    protected static $instance;
    /**
     * @var array<string, string>
     */
    protected array $defaults = [];

    public function __construct()
    {
        $this->defaults = [
            SettingConstants::SETTING_SYSTEM_LANGUAGE => 'en',
            SettingConstants::SETTING_TIME_FORMAT => '24h',
            SettingConstants::SETTING_SYSTEM_COLOR_MODE => 'dark',
            SettingConstants::SETTING_DAY_START_HOUR => '07:00',
            SettingConstants::SETTING_DAY_END_HOUR => '21:00',
            SettingConstants::SETTING_FIRST_WEEK_DAY => 'Monday',
        ];
    }

    public static function get(): UserSettingDefault
    {
        if (self::$instance === null) self::$instance = new UserSettingDefault();

        return static::$instance;
    }

    /**
     * @return array<string, string>
     */
    public function getAllDefaultValues(): array
    {
        return $this->defaults;
    }

    public function getDefaultValue(string $setting_name): ?string
    {
        return $this->defaults[$setting_name] ?? null;
    }

}