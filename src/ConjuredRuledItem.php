<?php


namespace App;


class ConjuredRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        if ($this->hasQuality()) {
            $this->decreaseQuality();
        }
        if ($this->hasQuality()) {
            $this->decreaseQuality();
        }
    }
}