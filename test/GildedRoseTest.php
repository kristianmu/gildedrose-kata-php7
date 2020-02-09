<?php

namespace App;

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDecreaseGenericRuledItemQuality()
    {
        $genericItem = $this->generateGenericRuledItemWithDefaultQuality();
        $gildedRose = new GildedRose([$genericItem]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_AFTER_ONE_DAY, $genericItem->quality());
    }

    /**
     * @test
     */
    public function itShouldDecreaseGenericRuledItemSellByDate()
    {
        $genericItem = $this->generateGenericRuledItemWithDefaultQuality();
        $gildedRose = new GildedRose([$genericItem]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_SELL_IN_DAYS_AFTER_ONE_DAY, $genericItem->sell_in());
    }

    /**
     * @test
     */
    public function itShouldNeverHaveANegativeQualityGenericItems()
    {
        $genericItem = $this->generateGenericRuledItemWithZeroQuality();
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

        $this->assertEquals(self::DEFAULT_QUALITY_INCREASED_TWICE_FASTER_AFTER_ONE_DAY, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldIncreaseQualityThreeTimesFasterIfBackStagePassRuledItemDateIsSmallerThan5Days()
    {
        $item = $this->generate5DaysBackStagePassRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_INCREASED_THREE_TIMES_FASTER_AFTER_ONE_DAY, $item->quality());
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
    public function itShouldDecreaseTwiceFasterTheQualityForConjuredRuledItem()
    {
        $item = $this->generateConjuredRuledItem();
        $gildedRose = new GildedRose([$item]);

        $gildedRose->updateQuality();

        $this->assertEquals(self::DEFAULT_QUALITY_DECREASED_TWICE_FASTER_AFTER_ONE_DAY, $item->quality());
    }

    /**
     * @test
     */
    public function itShouldAgeAllItemsAccordingToFullRulesSet()
    {
        $expected = file_get_contents(__DIR__ . "/goldenmaster.txt");
        $result = shell_exec("php ".__DIR__."/../fixtures/texttest_fixture.php");
        $this->assertEquals($expected, $result);
    }


    // =============================== Helpers =====================================================

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
    const DEFAULT_QUALITY_INCREASED_TWICE_FASTER_AFTER_ONE_DAY = self::DEFAULT_INITIAL_QUALITY + 2;
    const DEFAULT_QUALITY_INCREASED_THREE_TIMES_FASTER_AFTER_ONE_DAY = self::DEFAULT_INITIAL_QUALITY + 3;
    const DEFAULT_QUALITY_DECREASED_TWICE_FASTER_AFTER_ONE_DAY = self::DEFAULT_INITIAL_QUALITY - 2;

    private function generateRegularItem($name = self::REGULAR_ITEM_NAME, $quality = self::DEFAULT_INITIAL_QUALITY, $sell_by = self::IRRELEVANT_SELL_IN_DAYS): Item
    {
        return new Item($name, $sell_by, $quality);
    }

    private function generateAgedBrieItem(): Item
    {
        $item = $this->generateRegularItem();
        $item->name = AgedBrieRuledItem::ITEM_NAME;

        return $item;
    }

    private function generateBackStageItem(): Item
    {
        return $this->generateRegularItem(
            BackstagePassRuledItem::ITEM_NAME,
            self::DEFAULT_INITIAL_QUALITY,
            self::DEFAULT_SELL_IN_DAYS
        );
    }

    private function generateGenericRuledItemWithDefaultQuality(): GenericRuledItem
    {
        $item = $this->generateRegularItem();

        return new GenericRuledItem($item);
    }

    private function generateGenericRuledItemWithZeroQuality(): GenericRuledItem
    {
        $item = $this->generateRegularItem();
        $item->quality = 0;

        return new GenericRuledItem($item);
    }

    private function generateAgedBrieRuledItemWithZeroQuality(): AgedBrieRuledItem
    {
        $item = $this->generateAgedBrieItem();
        $item->quality = 0;

        return new AgedBrieRuledItem($item);
    }

    private function generateAgedBrieRuledItemWithFiftyQuality(): AgedBrieRuledItem
    {
        $brieItem = $this->generateAgedBrieItem();
        $brieItem->quality = 50;

        return new AgedBrieRuledItem($brieItem);
    }

    private function generateSulfurasRuledItem(): SulfurasRuledItem
    {
        $sulfurasItem = $this->generateRegularItem(
            SulfurasRuledItem::ITEM_NAME,
            self::MAXIMUM_ITEM_QUALITY
        );

        return new SulfurasRuledItem($sulfurasItem);
    }

    private function generateExpiredBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageItem();
        $backStagePassItem->sell_in = self::EXPIRED;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generate10DaysBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageItem();
        $backStagePassItem->sell_in = self::SELL_IN_TEN_DAYS;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generate5DaysBackStagePassRuledItem(): BackstagePassRuledItem
    {
        $backStagePassItem = $this->generateBackStageItem();
        $backStagePassItem->sell_in = self::SELL_IN_FIVE_DAYS;

        return new BackstagePassRuledItem($backStagePassItem);
    }

    private function generateBackStageItemItemWithFiftyQuality(): BackstagePassRuledItem
    {
        $backStageItem = $this->generateBackStageItem();
        $backStageItem->quality = self::MAXIMUM_ITEM_QUALITY;

        return new BackstagePassRuledItem($backStageItem);
    }

    private function generateConjuredRuledItem(): ConjuredRuledItem
    {
        $conjuredItem = $this->generateRegularItem(
            BackstagePassRuledItem::ITEM_NAME,
            self::DEFAULT_INITIAL_QUALITY,
            self::DEFAULT_SELL_IN_DAYS
        );

        return new ConjuredRuledItem($conjuredItem);
    }
}
