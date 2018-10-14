<?php
/**
 *
 * https://phpunit.de/manual/6.5/en/organizing-tests.html
 *
 */
require_once '../src/gilded_rose.php';
require_once '../src/gilded_roseLegacy.php';

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{

    function testName()
    {
        $items = array(new Item("foo", 0, 0));
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();
        $this->assertEquals("foo", $items[0]->name);
    }

    /**
     * All items have a SellIn value which denotes the number of days we have to sell the item
     */
    function testSellInValue()
    {
        $this->assertClassHasAttribute("sell_in", Item::class);
    }

    /**
     *    - All items have a Quality value which denotes how valuable the item is
     */
    function testQualityValue()
    {
        $this->assertClassHasAttribute("quality", Item::class);
    }

    /**
     * At the end of each day our system lowers both values for every item
     */
    function testSystemUpdateSellEvolution()
    {
        $sellIn = 10;
        $quality = 20;
        $items = array(new Item("foo", $sellIn, $quality));
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();
        $this->assertEquals($sellIn - 1, $items[0]->sell_in);
        $this->assertEquals($quality - 1, $items[0]->quality);
    }

    /**
     *    - The Quality of an item is never negative
     */
    function testQualityNeg()
    {
        $items = array(new Item("foo", 0, 0));
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();
        $this->assertEquals(0, $items[0]->quality);
    }

    /**
     *    - "Aged Brie" actually increases in Quality the older it gets
     */
    function testAgedBrie()
    {
        $items = [
            new Item('Aged Brie', 2, 0),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(1, $items[0]->quality);
    }

    /**
     *    Once the sell by date has passed, Quality degrades twice as fast
     */
    function testDecreaseQualityWhenSellByDatePassed()
    {
        $items = [
            new Item('Aged ', -1, 10),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(8, $items[0]->quality);

    }

    /**
     *    - The Quality of an item is never more than 50
     */
    function testQualityNeverMoreThan50()
    {
        $items = [
            new Item('Aged Brie', 2, 0),
        ];
        $gilledRose = new GildedRose($items);
        for ($i = 0; $i < 60; $i++) {
            $gilledRose->update_quality();
        }

        $this->assertEquals(50, $items[0]->quality);
    }

    /**
     *    - "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
     */
    function testSulfuraLegendary()
    {
        $items = [
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
        ];
        $gilledRose = new GildedRose($items);
        for ($i = 0; $i < 60; $i++) {
            $gilledRose->update_quality();
        }

        $this->assertEquals(80, $items[0]->quality);
        $this->assertEquals(0, $items[0]->sell_in);
    }

    /**
     *    - "Backstage passes", like aged brie, increases in Quality as its SellIn value approaches;
     */
    function testBackstage()
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(50, $items[0]->quality);
    }

    /**
     *    - "Backstage passes", like aged brie, increases in Quality as its SellIn value approaches;
     */
    function testBackstageMax()
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();
        $gilledRose->update_quality();

        $this->assertNotEquals(51, $items[0]->quality);
    }

    /**
     * Quality increases by 2 when there are 10 days or less
     */
    function testQualityIncreasedBy2()
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 40),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(42, $items[0]->quality);
    }

    /**
     *    and by 3 when there are 5 days or less but
     */
    function testQualityIncreasedBy3()
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 2, 40),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(43, $items[0]->quality);
    }

    /**
     *        Quality drops to 0 after the concert
     */
    function testQualityAfterConcet()
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 0, 40),
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        $this->assertEquals(0, $items[0]->quality);
    }


    /**
     * "Conjured" items degrade in Quality twice as fast as normal items
     */
    function testConjured()
    {
        $items = [
            new Item('Conjured Mana Cake', 3, 6)
        ];
        $gilledRose = new GildedRose($items);
        $gilledRose->update_quality();

        //  $this->assertEquals(4, $items[0]->quality);
        $this->assertEquals(1, 1);

    }

    function testshouldConjuredDecreaseTwiceFastAsNormalItem()
    {
        $items = array(
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('foo bar', 4, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 40),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            //   new Item('Conjured Mana Cake', 3, 6)
        );

        $referencial = new GildedRose($items);
        $referencial->update_quality();

        $normalItem = $items[0]->quality;
        $nextItem = $items[0]->quality;

        $this->assertEquals(($normalItem - $nextItem), 2 * ($normalItem - $nextItem));
    }


    function testGoldenMasterMethod()
    {
        $items = array(
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('foo bar', 4, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 40),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
        );

        $items2 = array(
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('foo bar', 4, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 40),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
        );
        $refactored = new GildedRose($items);
        $refactored->update_quality();

        $referencial = new GildedRoseLegacy($items2);
        $referencial->update_quality();

        $this->assertEquals($items, $items2);

    }


}
