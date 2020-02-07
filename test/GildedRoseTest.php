<?php

namespace App;

class GildedRoseTest extends \PHPUnit\Framework\TestCase {
    public function testFoo() {
        $items      = [new Item("foo", 0, 0)];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertEquals("fixme", $items[0]->name);
    }

    public function testName()
    {
        $outputFileName = __DIR__ . '/result.txt';
        $output = fopen($outputFileName, 'wb');

        fputs($output, "OMGHAI!\n");

        $items = array(
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            new Item('Conjured Mana Cake', 3, 6)
        );

        $app = new GildedRose($items);

        $days = 2;
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

        $this->assertTrue($this->files_are_equal($outputFileName, __DIR__ . "/goldenmaster.txt"));


    }

    private function files_are_equal($a, $b)
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



}
