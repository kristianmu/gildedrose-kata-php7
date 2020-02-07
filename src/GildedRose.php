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
            if ($item->name != self::ITEM_NAME_AGED_BRIE and $item->name != self::ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT) {
                if ($item->quality > 0) {
                    if ($item->name != self::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS) {
                        $item->quality = $item->quality - 1;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + 1;
                    if ($item->name == self::ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT) {
                        if ($item->sell_in < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                        if ($item->sell_in < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + 1;
                            }
                        }
                    }
                }
            }
            
            if ($item->name != self::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS) {
                $item->sell_in = $item->sell_in - 1;
            }
            
            if ($item->sell_in < 0) {
                if ($item->name != self::ITEM_NAME_AGED_BRIE) {
                    if ($item->name != self::ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT) {
                        if ($item->quality > 0) {
                            if ($item->name != self::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS) {
                                $item->quality = $item->quality - 1;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + 1;
                    }
                }
            }
        }
    }
}

