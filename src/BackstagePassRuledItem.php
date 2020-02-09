<?php


namespace App;


class BackstagePassRuledItem extends GenericRuledItem
{
    const ITEM_NAME = 'Backstage passes to a TAFKAL80ETC concert';

    public function updateItemQuality(): void
    {
        $this->increaseQuality();
        if ($this->item->sell_in <= 10) {
            $this->increaseQuality();
        }

        if ($this->item->sell_in <= 5) {
            $this->increaseQuality();
        }

        if ($this->isExpired()) {
            $this->item->quality = 0;
        }
    }

    protected function increaseQuality(): void
    {
        if (!$this->hasMaximumQuality()) {
            $this->item->quality = $this->item->quality + 1;
        }
    }
}