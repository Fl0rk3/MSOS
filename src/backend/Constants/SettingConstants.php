<?php

declare(strict_types=1);

namespace MSOS\backend\Constants;

class SettingConstants
{
    public const SETTING_SYSTEM_LANGUAGE = 'system_language';
    public const SETTING_TIME_FORMAT = 'time_format';
    public const SETTING_SYSTEM_COLOR_MODE = 'system_color_mode';
    public const SETTING_DAY_START_HOUR = 'day_start_hour';
    public const SETTING_DAY_END_HOUR = 'day_end_hour';
    public const SETTING_FIRST_WEEK_DAY = 'first_week_day';
    private const HOURS = [
        '00:00' => '00:00',
        '01:00' => '01:00',
        '02:00' => '02:00',
        '03:00' => '03:00',
        '04:00' => '04:00',
        '05:00' => '05:00',
        '06:00' => '06:00',
        '07:00' => '07:00',
        '08:00' => '08:00',
        '09:00' => '09:00',
        '10:00' => '10:00',
        '11:00' => '11:00',
        '12:00' => '12:00',
        '13:00' => '13:00',
        '14:00' => '14:00',
        '15:00' => '15:00',
        '16:00' => '16:00',
        '17:00' => '17:00',
        '18:00' => '18:00',
        '19:00' => '19:00',
        '20:00' => '20:00',
        '21:00' => '21:00',
        '22:00' => '22:00',
        '23:00' => '23:00',
        '24:00' => '24:00',
    ];

    public const SETTING_OPTIONS = [
        self::SETTING_SYSTEM_LANGUAGE => [
            'en' => 'English',
        ],
        self::SETTING_TIME_FORMAT => [
            '12h' => '12h',
            '24h' => '24h',
        ],
        self::SETTING_SYSTEM_COLOR_MODE => [
//            'light' => 'Light',
            'dark' => 'Dark',
        ],
        self::SETTING_DAY_START_HOUR => self::HOURS,
        self::SETTING_DAY_END_HOUR => self::HOURS,
        self::SETTING_FIRST_WEEK_DAY => [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ]
    ];
}
