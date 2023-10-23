<?php

class Links
{
    function getLinksToHTML($links): string
    {
        $htmlResult = "";

        foreach ($links as $link) {
            $htmlResult .= "
            <div class='right_nav_links_bar_link'>
            <a href='" . $link['url'] . "'>" . $link['name'] . "</a>
            </div>
            ";
        }

        return $htmlResult;
    }
}
