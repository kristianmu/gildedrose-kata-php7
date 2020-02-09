<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\AgedBrieRuledItem;
use App\BackstagePassRuledItem;
use App\ConjuredRuledItem;
use App\GenericRuledItem;
use App\GildedRose;
use App\Item;
use App\SulfurasRuledItem;

echo "OMGHAI!\n";

$items = array(
    new GenericRuledItem(new Item('+5 Dexterity Vest', 10, 20)),
    new AgedBrieRuledItem(new Item('Aged Brie', 2, 0)),
    new GenericRuledItem(new Item('Elixir of the Mongoose', 5, 7)),
    new SulfurasRuledItem(new Item('Sulfuras, Hand of Ragnaros', 0, 80)),
    new SulfurasRuledItem(new Item('Sulfuras, Hand of Ragnaros', -1, 80)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49)),
    new ConjuredRuledItem(new Item('Conjured Mana Cake', 3, 6))
);

$app = new GildedRose($items);

$days = 2;
for ($i = 0; $i < $days; $i++) {
    echo "-------- day $i --------\n";
    echo "name, sellIn, quality\n";

    foreach ($items as $item) {
        echo $item . PHP_EOL;
    }

    echo PHP_EOL;

    $app->updateQuality();
}