<?php

declare(strict_types=1);

namespace MSOS\backend\ctrl;

final class LinksRenderer
{
    public static function toHtml(array $links): string
    {
        $out = '';
        foreach ($links as $link) {
            $out .= "
            <div class='right_nav_links_bar_link'>
                <a href='" . $link['url'] . "' target='_blank' rel='noopener noreferrer'>" . $link['name'] . "</a>
                <div class='right_nav_links_bar_link_delete' onclick='deleteLink(`" . $link['name'] . "`)'><i class='fa-solid fa-trash'></i></div>
            </div>
            ";
        }

        return $out;
    }
}