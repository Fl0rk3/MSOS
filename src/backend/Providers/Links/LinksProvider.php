<?php

declare(strict_types=1);

namespace MSOS\backend\Providers\Links;

interface LinksProvider
{
    /** @return list<array{name:string,url:string}> */
    public function forUser(int $userId): array;
}