<?php

namespace App;

class GenericRuledItem
{
    /** @var Item */
    protected Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function quality()
    {
        return $this->item->quality;
    }

    public function sell_in()
    {
        return $this->item->sell_in;
    }

    public function updateItemQuality(): void
    {
        if ($this->hasQuality()) {
            $this->decreaseQuality();
        }
    }

    public function updateItemSellByDate(): void
    {
        $this->item->sell_in = $this->item->sell_in - 1;
    }

    private function hasQuality(): bool
    {
        return $this->item->quality > 0;
    }

    private function decreaseQuality(): void
    {
        $this->item->quality = $this->item->quality - 1;
    }

    protected function hasMaximumQuality(): bool
    {
        return $this->item->quality >= 50;
    }

    protected function increaseQuality(): void
    {
        $this->item->quality = $this->item->quality + 1;
    }

    public function __toString() {
        return "{$this->item->name}, {$this->item->sell_in}, {$this->item->quality}";
    }

    protected function isExpired()
    {
        return $this->item->sell_in <= 0;
    }
}