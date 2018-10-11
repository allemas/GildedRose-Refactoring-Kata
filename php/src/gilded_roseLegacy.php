<?php

class GildedRoseLegacy
{

    private $items;

    function __construct($items)
    {
        $this->items = $items;
    }

    function update_quality()
    {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case 'Sulfuras, Hand of Ragnaros':
                    break;
                case 'Aged Brie':
                    $this->updateAgedBrie($item);
                    break;
                case 'Backstage passes to a TAFKAL80ETC concert':
                    $this->UpdateBackstage($item);
                    break;
                default:
                    $this->UpdateDefault($item);
                    break;
            }
        }
    }

    /**
     * @param $item
     */
    public function increaseQuality($item): void
    {
        if ($item->quality < 50) {
            $item->quality = $item->quality + 1;
        }
    }

    /**
     * @param $item
     */
    public function decreaseSellIn($item): void
    {
        $item->sell_in = $item->sell_in - 1;
    }

    /**
     * @param $item
     */
    public function updateAgedBrie($item): void
    {
        $this->increaseQuality($item);
        if ($item->sell_in < 0) {
            $this->increaseQuality($item);
        }
        $this->decreaseSellIn($item);
    }

    /**
     * @param $item
     */
    public function UpdateBackstage($item): void
    {
        $this->increaseQuality($item);
        $this->decreaseSellIn($item);

        if ($item->sell_in < 11) {
            $this->increaseQuality($item);
        }
        if ($item->sell_in < 6) {
            $this->increaseQuality($item);
        }
        if ($item->sell_in < 0) {
            $item->quality = $item->quality - $item->quality;
        }
    }

    /**
     * @param $item
     */
    public function UpdateDefault($item): void
    {
        $this->decreaseSellIn($item);
        if ($item->quality > 0) {
            $this->decreaseQuality($item);
        }
        if ($item->sell_in < 0) {
            $this->decreaseQuality($item);
        }
    }

    /**
     * @param $item
     */
    public function decreaseQuality($item): void
    {
        $item->quality = $item->quality - 1;
    }

}
