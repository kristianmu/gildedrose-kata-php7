<?php

namespace App;

class GenericRuledItem
{
    /**
     * @var Item
     */
    private Item $item;

    /**
     * RegularItemRules constructor.
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function quality() {
        return $this->item->quality;
    }

    /**
     * @param $item
     */
    public function updateItemQuality(): void
    {
        if ($this->hasQuality()) {
            $this->decreaseQuality();
        }
    }

    /**
     * @param $item
     * @return bool
     */
    private function hasQuality(): bool
    {
        return $this->item->quality > 0;
    }

    /**
     * @param $item
     */
    private function decreaseQuality(): void
    {
        $this->item->quality = $this->item->quality - 1;
    }


}