<?php


namespace App;


class AgedBrieRuledItem extends GenericRuledItem
{
    const ITEM_NAME = 'Aged Brie';

    public function updateItemQuality(): void
    {
        if (!$this->hasMaximumQuality()) {
            $this->increaseQuality();
        }
    }
}