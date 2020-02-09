<?php


namespace App;


class AgedBrieRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        if (!$this->hasMaximumQuality()) {
            $this->increaseQuality();
        }
    }
}