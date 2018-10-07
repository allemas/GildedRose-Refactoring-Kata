<?php

require_once 'gilded_rose.php';

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{

    function testFoo()
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




}
