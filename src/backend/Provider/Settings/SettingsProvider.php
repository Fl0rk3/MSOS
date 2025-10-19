<?php

declare(strict_types=1);

namespace MSOS\backend\Provider\Settings;

interface SettingsProvider
{
    /** @return array<string, string> */
    public function forUser(int $user_id): array;
}