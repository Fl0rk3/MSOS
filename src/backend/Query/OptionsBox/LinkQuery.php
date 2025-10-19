<?php

namespace MSOS\backend\Query\OptionsBox;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use MSOS\backend\Entity\Link;
use MSOS\backend\Entity\User;
use MSOS\backend\Provider\Links\DoctrineLinksProvider;

class LinkQuery
{
    private DoctrineLinksProvider $doctrineLinksProvider;

    function __construct(
        private EntityManagerInterface $em,
    )
    {
        $this->doctrineLinksProvider = new DoctrineLinksProvider($this->em);
    }

    /**
     * @throws ORMException
     */
    function addLink(int $user_id, string $name, string $url): bool
    {
        $userLinks = $this->doctrineLinksProvider->forUser($user_id);

        foreach ($userLinks as $link) {
            if ($name == $link['name'] || $url == $link['url']) {
                return false;
            }
        }

        $userRef = $this->em->getReference(User::class, $user_id);
        if ($userRef === null) {
            return false;
        }
        $link = new Link($userRef, $name, $url);
        $this->em->persist($link);
        $this->em->flush();

        return true;
    }

    function removeLink(int $user_id, string $name): bool
    {
        $link = $this->em->getRepository(Link::class)->findOneBy(['user' => $user_id, 'name' => $name]);

        if (!$link) {
            return false;
        }

        $this->em->remove($link);
        $this->em->flush();

        return true;
    }
}
