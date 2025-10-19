<?php

declare(strict_types=1);

namespace MSOS\backend\Provider\Links;

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
    public function forUser(int $user_id): array
    {
        $user_ref = $this->em->getReference(User::class, $user_id);
        $links = $this->em->getRepository(Link::class)->findBy(['user' => $user_ref], ['name' => 'ASC']);
        return array_map(
            fn(Link $l) => ['name' => $l->getName(), 'url' => $l->getUrl()],
            $links
        );
    }
}