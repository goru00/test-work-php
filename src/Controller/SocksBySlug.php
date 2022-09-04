<?php

namespace App\Controller;

use App\Entity\Socks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class SocksBySlug extends AbstractController
{
    public function __invoke(string $slug)
    {
        $socks = $this->getDoctrine()
            ->getRepository(socks::class)
            ->findBy(
                ['slug' => $slug]
            );
        if (!$socks) {
            throw $this->createNotFoundException(
                "Параметры запроса отсутствуют или имеют некорректный формат"
            );
        }

        return $socks;
    }
}
