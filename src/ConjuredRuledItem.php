<?php


namespace App;


class ConjuredRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        for ($times = 0; $times < 2; $times++){
            if ($this->hasQuality()) {
                $this->decreaseQuality();
            }
        }
    }
}