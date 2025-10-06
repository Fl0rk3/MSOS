<?php

declare(strict_types=1);

namespace MSOS\backend\Providers\Links;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Entity\Link;
use MSOS\backend\Entity\User;

final class DoctrineLinksProvider implements LinksProvider
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @throws ORMException
     */
    public function forUser(int $userId): array
    {
        $userRef = $this->em->getReference(User::class, $userId);
        $links = $this->em->getRepository(Link::class)->findBy(['user' => $userRef], ['name' => 'ASC']);
        return array_map(
            fn(Link $l) => ['name' => $l->getName(), 'url' => $l->getUrl()],
            $links
        );
    }
}