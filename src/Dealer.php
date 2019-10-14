<?php

namespace MarcusCampos\Dealer;

use MarcusCampos\Dealer\Negotiation;
use Illuminate\Database\Eloquent\Collection;

class Dealer
{
    /**
     * @var Negotiation
     */
    private $negotiation;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->negotiation = new Negotiation();
    }

    /**
     * Negotiator
     *
     * @param string $query
     * @return Collection
     */
    public function negotiate(string $query)
    {
        return $this->negotiation->negotiate($query);
    }
}
