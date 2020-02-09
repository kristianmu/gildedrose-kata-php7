<?php


namespace App;


class AgedBrieRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        $this->item->quality = $this->item->quality + 1;
    }
}