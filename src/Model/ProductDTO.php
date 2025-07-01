<?php

namespace App\Model;

class ProductDTO
{
    public string $brand;
    public string $article;
    public string $name;
    public string $quantity;
    public int $price;
    public int $delivery_duration;
    public string $vendorId;
    public string $warehouseAlias;

    /**
     * Модель данных продукта
     * @param string $brand бренд
     * @param string $article артикль
     * @param object $warehouseOffer складские данные
     */
    public function __construct(string $brand, string $article, object $warehouseOffer)
    {
        $this->brand = $brand;
        $this->article = $article;
        $this->name = $warehouseOffer->name;
        $this->quantity = $warehouseOffer->quantity;
        $this->price = 100 * $warehouseOffer->price;
        $this->delivery_duration = 24 * 60 * 60 * $warehouseOffer->delivery_period;
        $this->vendorId = $warehouseOffer->id;
        $this->warehouseAlias = $warehouseOffer->warehouse_code;
    }


}
