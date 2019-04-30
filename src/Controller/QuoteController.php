<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Quote;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuoteController extends AbstractFOSRestController
{
    /**
     * @Annotations\View()
     */
    public function cgetQuotesAction()
    {
        $quotes = $this->getDoctrine()->getRepository(Quote::class)->findAll();

        return $this->handleView($this->view($quotes));
    }

    /**
     * @Annotations\View(templateVar="quote")
     *
     * @param type $id
     */
    public function getQuoteAction($id)
    {
        $quote = $this->getDoctrine()->getRepository(Quote::class)->find($id);

        if (!$quote) {
            throw $this->createNotFoundException('Quote does not exists');
        }

        return $this->handleView($this->view($quote));
    }

    /**
     * @Annotations\View()
     *
     * @param Request $request
     */
    public function postQuoteAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository(Author::class)->findOneByName($data['author']);
        if (!$author) {
            $author = (new Author())
                ->setName($data['author']);

            $em->persist($author);
        }

        $quote = (new Quote())
            ->setAuthor($author)
            ->setQuote($data['quote']);

        $em->persist($quote);
        $em->flush();

        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }
}
