<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Quote;
use Doctrine\Common\Persistence\ObjectManager;
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
     * @param int $id
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
        $author = $this->getAuthor($em, $data['author']);

        $quote = (new Quote())
            ->setAuthor($author)
            ->setQuote($data['quote']);

        $em->persist($quote);
        $em->flush();

        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
    }

    /**
     * @Annotations\View()
     *
     * @param Request $request
     * @param int $id
     */
    public function putQuoteAction(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();
        $author = $this->getAuthor($em, $data['author']);

        $quote = $this->getDoctrine()->getRepository(Quote::class)->find($id);
        if (!$quote) {
            $quote = new Quote();

            $statusCode = Response::HTTP_CREATED;
        } else {
            $statusCode = Response::HTTP_NO_CONTENT;
        }
        $quote
            ->setAuthor($author)
            ->setQuote($data['quote']);

        $em->persist($quote);
        $em->flush();

        return $this->handleView($this->view(['status' => 'ok'], $statusCode));
    }

    /**
     * @Annotations\View()
     *
     * @param int $id
     */
    public function deleteQuoteAction(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $quote = $em->getRepository(Quote::class)->find($id);

        $em->remove($quote);
        $em->flush();

        return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_NO_CONTENT));
    }

    /**
     * @param ObjectManager $em
     * @param string $name
     * @return Author
     */
    private function getAuthor(ObjectManager $em, string $name): Author
    {
        $author = $em->getRepository(Author::class)->findOneByName($name);
        if (!$author) {
            $author = (new Author())
                ->setName($name);

            $em->persist($author);
        }

        return $author;
    }
}
