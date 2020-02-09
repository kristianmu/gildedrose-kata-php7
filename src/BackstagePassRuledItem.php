<?php


namespace App;


class BackstagePassRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        if ($this->isExpired()) {
            $this->item->quality = 0;
        }
    }
}