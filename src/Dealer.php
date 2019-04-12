<?php

namespace MarcusCampos\Dealer;

use MarcusCampos\Dealer\Negotiation;

class Dealer
{
    /**
     * @var Negociation
     */
    private $negociation;
    
    /**
     * Constructor
     *
     * @param Negociation $negociation
     */
    public function __construct(Negociation $negociation)
    {
        $this->negociation = $negociation;
    }

    /**
     * Negociator
     *
     * @param string $query
     * @return Collection
     */
    public function negociate(string $query)
    {
        return $this->negociation->negociate($query);
    }
}
