<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\AgedBrieRuledItem;
use App\BackstagePassRuledItem;
use App\ConjuredRuledItem;
use App\GenericRuledItem;
use App\GildedRose;
use App\Item;
use App\SulfurasRuledItem;

$outputFileName = __DIR__ . '/goldenmaster.txt';
$output = fopen( $outputFileName, 'wb');

fputs($output, "OMGHAI!\n");

$items = array(
    new GenericRuledItem(new Item('+5 Dexterity Vest', 10, 20)),
    new AgedBrieRuledItem(new Item('Aged Brie', 2, 0)),
    new GenericRuledItem(new Item('Elixir of the Mongoose', 5, 7)),
    new SulfurasRuledItem(new Item('Sulfuras, Hand of Ragnaros', 0, 80)),
    new SulfurasRuledItem(new Item('Sulfuras, Hand of Ragnaros', -1, 80)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49)),
    new BackstagePassRuledItem(new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49)),
    // this conjured item does not work properly yet
    new ConjuredRuledItem(new Item('Conjured Mana Cake', 3, 6))
);

$app = new GildedRose($items);

$days = 2;
if (count($argv) > 1) {
    $days = (int)$argv[1];
}

for ($i = 0; $i < $days; $i++) {
    fputs($output, "-------- day $i --------\n");
    fputs($output, "name, sellIn, quality\n");
    foreach ($items as $item) {
        fputs($output, $item . PHP_EOL);
    }
    fputs($output, PHP_EOL);


    $app->updateQuality();
}

fclose($output);

echo files_are_equal($outputFileName, __DIR__ . "/goldenmaster.txt") ? "Equal" : "Not equal";


function files_are_equal($a, $b)
{
    // Check if filesize is different
    if (filesize($a) !== filesize($b))
        return false;
    // Check if content is different
    $ah = fopen($a, 'rb');
    $bh = fopen($b, 'rb');
    $result = true;
    while (!feof($ah)) {
        if (fread($ah, 8192) != fread($bh, 8192)) {
            $result = false;
            break;
        }
    }
    fclose($ah);
    fclose($bh);
    return $result;
}