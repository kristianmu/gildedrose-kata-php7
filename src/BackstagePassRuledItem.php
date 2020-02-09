<?php


namespace App;


class BackstagePassRuledItem extends GenericRuledItem
{
    public function updateItemQuality(): void
    {
        $this->increaseQuality();
        if ($this->item->sell_in <= 10) {
            $this->increaseQuality();
        }

        if ($this->isExpired()) {
            $this->item->quality = 0;
        }
    }
}