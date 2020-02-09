<?php

namespace App;

final class GildedRose
{
    /** @var GenericRuledItem[] $items */
    private $items = [];

    /**
     * GildedRose constructor.
     * @param GenericRuledItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality()
    {
        foreach ($this->items as $item) {
            $item->updateItemQuality();
            $item->updateItemSellByDate();
        }
    }
}

