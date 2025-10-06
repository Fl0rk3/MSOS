<?php

namespace MSOS\Php\OptionsBox;

class Links
{
    /**
     * @param array<int, array<string, mixed>> $links
     */
    function getLinksToHTML(array $links): string
    {
        $htmlResult = "";

        foreach ($links as $link) {
            $htmlResult .= "
            <div class='right_nav_links_bar_link'>
                <a href='" . $link['url'] . "' target='_blank' rel='noopener noreferrer'>" . $link['name'] . "</a>
                <div class='right_nav_links_bar_link_delete' onclick='deleteLink(`" . $link['name'] . "`)'><i class='fa-solid fa-trash'></i></div>
            </div>
            ";
        }

        return $htmlResult;
    }
}
