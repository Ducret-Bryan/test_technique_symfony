<?php

namespace App\Service;

class FilterService
{
    private $departure;
    private $return;
    private $price;

    public function __construct($form)
    {
        $this->departure = $form->get('departureDate')->getData();
        $this->return = $form->get('returnDate')->getData();
        $this->price = $form->get('price')->getData();
    }

    public function getFilterData($disponibilities)
    {
        $dataFilter = [];
        foreach ($disponibilities as $disponibility) {
            if ($disponibility->isStatus() == true && $this->checkDate($disponibility->getdepartureDate(), $disponibility->getReturnDate()) && $this->checkPrice($disponibility->getPrice()))
                array_push($dataFilter, $disponibility);
        }
        return $dataFilter;
    }

    private function checkDate($start, $end)
    {
        if ($this->departure != null && $this->return != null)
            return ($this->departure <= $start && $this->return >= $end);
        if ($this->departure == null && $this->return == null)
            return true;
        else if ($this->departure == null)
            return ($this->return >= $end);

        else  if ($this->return == null) {
            return ($this->departure <= $start);
        }
    }

    private function checkPrice($price)
    {
        return ($this->price == $price) || ($this->price == null);
    }
}
