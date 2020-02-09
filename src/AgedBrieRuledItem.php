<?php


namespace App;


class AgedBrieRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        if (!$this->hasMaximumQuality()) {
            $this->item->quality = $this->item->quality + 1;
        }
    }

    private function hasMaximumQuality(): bool
    {
        return $this->item->quality >= 50;
    }
}