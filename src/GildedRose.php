<?php

namespace App;

final class GildedRose {

    const ITEM_NAME_AGED_BRIE = 'Aged Brie';
    const ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT = 'Backstage passes to a TAFKAL80ETC concert';
    const ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS = 'Sulfuras, Hand of Ragnaros';
    private $items = [];

    public function __construct($items) {
        $this->items = $items;
    }

    public function updateQuality() {
        foreach ($this->items as $item) {
            if (!$this->isAgedBrie($item) and !$this->isBackStagePass($item)) {
                $this->decreaseQuality($item);
            } else {
                if (!$this->hasMaximumQuality($item)) {
                    $this->increaseQuality($item);
                    if ($this->isBackStagePass($item)) {
                        if ($item->sell_in < 11) {
                            if (!$this->hasMaximumQuality($item)) {
                                $this->increaseQuality($item);
                            }
                        }
                        if ($item->sell_in < 6) {
                            if (!$this->hasMaximumQuality($item)) {
                                $this->increaseQuality($item);
                            }
                        }
                    }
                }
            }
            
            if (!$this->isSulfura($item)) {
                $this->decreaseSellByDate($item);
            }
            
            if ($item->sell_in < 0) {
                if (!$this->isAgedBrie($item)) {
                    if (!$this->isBackStagePass($item)) {
                                $this->decreaseQuality($item);

                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if (!$this->hasMaximumQuality($item)) {
                        $this->increaseQuality($item);
                    }
                }
            }
        }
    }

    /**
     * @param $item
     * @return bool
     */
    private function isAgedBrie($item): bool
    {
        return ($item->name == self::ITEM_NAME_AGED_BRIE);
    }

    /**
     * @param $item
     * @return bool
     */
    private function isBackStagePass($item): bool
    {
        return ($item->name == self::ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT);
    }

    /**
     * @param $item
     * @return bool
     */
    private function isSulfura($item): bool
    {
        return ($item->name == self::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS);
    }

    /**
     * @param $item
     * @return bool
     */
    private function notExpired($item): bool
    {
        return $item->quality > 0;
    }

    /**
     * @param $item
     */
    private function decreaseQuality($item): void
    {
        if ($this->notExpired($item)) {
            if (!$this->isSulfura($item)) {
                $item->quality = $item->quality - 1;
            }
        }
    }

    /**
     * @param $item
     */
    private function increaseQuality($item): void
    {
        $item->quality = $item->quality + 1;
    }

    /**
     * @param $item
     * @return bool
     */
    private function hasMaximumQuality($item): bool
    {
        return ($item->quality >= 50);
    }

    /**
     * @param $item
     */
    private function decreaseSellByDate($item): void
    {
        $item->sell_in = $item->sell_in - 1;
    }
}

