<?php

namespace App;

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    const REGULAR_ITEM_NAME = "Regular Item";
    const DEFAULT_SELL_IN_DAYS = 10;
    const IRRELEVANT_SELL_IN_DAYS = 10;
    const DEFAULT_INITIAL_QUALITY = 10;
    const DEFAULT_QUALITY_AFTER_ONE_DAY = 9;
    const DEFAULT_SELL_IN_DAYS_AFTER_ONE_DAY = 9;
    const MAXIMUM_ITEM_QUALITY = 50;
    const EXPIRED = 0;
    const SELL_IN_TEN_DAYS = 10;
    const SELL_IN_FIVE_DAYS = 5;
    const DEFAULT_QUALITY_AFTER_ONE_DAY_TWICE_FASTER = self::DEFAULT_INITIAL_QUALITY + 2;
    const DEFAULT_QUALITY_AFTER_ONE_DAY_THREE_TIMES_FASTER = self::DEFAULT_INITIAL_QUALITY + 3;

    /**
     * @test
     */
    public function itShouldDecreaseQualityAtTheEndOfTheDayForRegularItems()
    {
        $item = $this->generateRegularItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_AFTER_ONE_DAY, $item->quality);
    }

    /**
     * @test
     */
    public function itShouldNeverHaveANegativeQualityRegularItems()
    {
        $item = $this->generateRegularItemWithZeroQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(0, $item->quality);
    }

    /**
     * @test
     */
    public function itShouldIncreaseInQualityIfAgedBrieItem()
    {
        $item = $this->generateBrieItemWithZeroQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(1, $item->quality);
    }

    /**
     * @test
     */
    public function itShouldNeverIncreaseInQualityOverFiftyIfAgedBrieItem()
    {
        $item = $this->generateBrieItemWithFiftyQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::MAXIMUM_ITEM_QUALITY, $item->quality);
    }

    /**
     * @test
     */
    public function itShouldNeverDecreaseQualityOfSulfuras()
    {
        $item = $this->generateSulfuras();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::MAXIMUM_ITEM_QUALITY, $item->quality);
    }

    /**
     * @test
     */
    public function itShouldNeverDecreaseSellByDateOfSulfuras()
    {
        $item = $this->generateSulfuras();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_SELL_IN_DAYS, $item->sell_in);
    }

    // ===== New tests by type

    /**
     * @test
     */
    public function itShouldDecreaseGenericItemQuality()
    {
        $genericItem = $this->genericItem();
        $gildedRose = new GildedRose([$genericItem]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_AFTER_ONE_DAY, $genericItem->quality());
    }

    /**
     * @test
     */
    public function itShouldDecreaseGenericItemSellByDate()
    {
        $genericItem = $this->genericItem();
        $gildedRose = new GildedRose([$genericItem]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_SELL_IN_DAYS_AFTER_ONE_DAY, $genericItem->sell_in());
    }

    /**
     * @test
     */
    public function itShouldNeverHaveANegativeQualityGenericItems()
    {
        $genericItem = $this->genericItemWithZeroQuality();
        $gildedRose = new GildedRose([$genericItem]);

        $gildedRose->updateQuality();

        $this->assertEquals(0, $genericItem->quality());
    }

    /**
     * @test
     */
    public function itShouldIncreaseInQualityIfAgedBrieRuledItem()
    {
        $item = $this->generateAgedBrieRuledItemWithZeroQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(1, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldNeverIncreaseInQualityOverFiftyIfAgedBrieRuledItem()
    {
        $item = $this->generateAgedBrieRuledItemWithFiftyQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::MAXIMUM_ITEM_QUALITY, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldNeverDecreaseQualityOfSulfurasRuledItem()
    {
        $item = $this->generateSulfurasRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::MAXIMUM_ITEM_QUALITY, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldNeverDecreaseSellByDateOfSulfurasRuledType()
    {
        $item = $this->generateSulfurasRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_SELL_IN_DAYS, $item->sell_in());
    }

    /**
     * @test
     */
    public function itShouldPutQualityToZeroIfBackStagePassRuledItemHasExpired()
    {
        $item = $this->generateExpiredBackStagePassRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(0, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldIncreaseQualityTwiceFasterIfBackStagePassRuledItemDateIsSmallerThan10Days()
    {
        $item = $this->generate10DaysBackStagePassRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_AFTER_ONE_DAY_TWICE_FASTER, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldIncreaseQualityThreeTimesFasterIfBackStagePassRuledItemDateIsSmallerThan5Days()
    {
        $item = $this->generate5DaysBackStagePassRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_AFTER_ONE_DAY_THREE_TIMES_FASTER, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldNeverIncreaseInQualityOverFiftyIfBackStagePassItem()
    {
        $item = $this->generateBackStageItemItemWithFiftyQuality();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::MAXIMUM_ITEM_QUALITY, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldAgeAllItemsAccordingToFullRulesSet()
    {
        $outputFileName = __DIR__ . '/result.txt';
        $output = fopen($outputFileName, 'wb');

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

        $this->assertEquals(file_get_contents($outputFileName), file_get_contents(__DIR__ . "/goldenmaster.txt"));
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

    private function generateRegularItem($name = self::REGULAR_ITEM_NAME, $quality = self::DEFAULT_INITIAL_QUALITY, $sell_by = self::IRRELEVANT_SELL_IN_DAYS): Item
    {
        return new Item($name, $sell_by, $quality);
    }

    private function generateRegularItemWithZeroQuality(): Item
    {
        $item = $this->generateRegularItem();
        $item->quality = 0;
        return $item;
    }

    private function generateBrieItemWithZeroQuality(): Item
    {
        $item = $this->generateRegularItemWithZeroQuality();
        $item->name = GildedRose::ITEM_NAME_AGED_BRIE;
        return $item;
    }

    private function generateBrieItemWithFiftyQuality(): Item
    {
        $brieItem = $this->generateBrieItemWithZeroQuality();
        $brieItem->quality = 50;
        return $brieItem;
    }

    private function generateSulfuras()
    {
        return $this->generateRegularItem(
            GildedRose::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS,
            self::MAXIMUM_ITEM_QUALITY
        );
    }

    private function genericItem(): GenericRuledItem
    {
        $item = $this->generateRegularItem();

        return new GenericRuledItem($item);
    }

    private function genericItemWithZeroQuality(): GenericRuledItem
    {
        $item = $this->generateRegularItemWithZeroQuality();

        return new GenericRuledItem($item);
    }

    private function generateAgedBrieRuledItemWithZeroQuality(): AgedBrieRuledItem
    {
        $item = $this->generateBrieItemWithZeroQuality();

        return new AgedBrieRuledItem($item);
    }

    private function generateAgedBrieRuledItemWithFiftyQuality(): AgedBrieRuledItem
    {
        $brieItem = $this->generateBrieItemWithZeroQuality();
        $brieItem->quality = 50;

        return new AgedBrieRuledItem($brieItem);
    }

    private function generateSulfurasRuledItem(): SulfurasRuledItem
    {
        $sulfurasItem = $this->generateRegularItem(
            GildedRose::ITEM_NAME_SULFURAS_HAND_OF_RAGNAROS,
            self::MAXIMUM_ITEM_QUALITY
        );

        return new SulfurasRuledItem($sulfurasItem);
    }

    private function generateBackStageRuledItem(): Item
    {
        return $this->generateRegularItem(
            GildedRose::ITEM_NAME_BACKSTAGE_PASSES_TO_A_TAFKAL_80_ETC_CONCERT,
            self::DEFAULT_INITIAL_QUALITY,
            self::MAXIMUM_ITEM_QUALITY
        );
    }

    private function generateExpiredBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageRuledItem();
        $backStagePassItem->sell_in = self::EXPIRED;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generate10DaysBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageRuledItem();
        $backStagePassItem->sell_in = self::SELL_IN_TEN_DAYS;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generate5DaysBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageRuledItem();
        $backStagePassItem->sell_in = self::SELL_IN_FIVE_DAYS;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generateBackStageItemItemWithFiftyQuality(): BackstagePassRuledItem
    {
        $backStageItem = $this->generateBackStageRuledItem();
        $backStageItem->quality = self::MAXIMUM_ITEM_QUALITY;

        return new BackstagePassRuledItem($backStageItem);
    }
}
