<?php

namespace App\Controller;

use App\Entity\Quote;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;

class QuoteController extends AbstractFOSRestController
{
    /**
     * @Annotations\View(templateVar="quote", populateDefaultVars=false)
     *
     * @param type $id
     */
    public function getQuoteAction($id)
    {
        $quote = $this->getDoctrine()->getRepository(Quote::class)->find($id);

        return $this->handleView($this->view($quote));
    }
}
