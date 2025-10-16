<?php

declare(strict_types=1);

namespace MSOS\backend\Provider\Links;

interface LinksProvider
{
    /** @return list<array{name:string,url:string}> */
    public function forUser(int $user_id): array;
}