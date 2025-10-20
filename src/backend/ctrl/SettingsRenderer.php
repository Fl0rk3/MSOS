<?php

declare(strict_types=1);

namespace MSOS\backend\ctrl;

use MSOS\backend\Constants\SettingConstants;

final class SettingsRenderer
{
    /**
     * @param array<string, string> $user_settings
     */
    public static function toHtml(array $user_settings): string
    {
        $out = '';
        foreach (SettingConstants::SETTING_OPTIONS as $setting_name => $setting_options) {
            $out .= "<div class='option_window_viewSettings_select'>";
            $out .= "<label for='" . $setting_name . "' data-i18n='option.settings." . $setting_name . "'>" . "</label>";
            $out .= "<select id='" . $setting_name . "'>";
            foreach ($setting_options as $value => $label) {
                $selected = '';
                if ($value == $user_settings[$setting_name]) {
                    $selected = ' selected ';
                }
                $out .= "<option value='" . $value . "'" . $selected . ">" . $label . "</option>";
            }
            $out .= "</select>";
            $out .= "</div>";
        }

        return $out;
    }
}