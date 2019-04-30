<?php

namespace App\Controller;

use App\Entity\Quote;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;

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
}
