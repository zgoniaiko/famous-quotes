<?php

namespace App\Controller;

use App\Entity\Author;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;

class AuthorController extends AbstractFOSRestController
{
    /**
     * @Annotations\View(templateVar="author")
     *
     * @param int $id
     */
    public function getAuthorAction($id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Author does not exists');
        }

        return $this->handleView($this->view($author));
    }
}
