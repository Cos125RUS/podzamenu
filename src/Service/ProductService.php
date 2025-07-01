<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as TransportExceptionInterfaceAlias;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Сервисы для ProductController
 */
class ProductService implements IProductService
{
    private const API_PATH = 'http://api.tmparts.ru/api/';
    private const STOCK_BY_ARTICLE_PATH = 'StockByArticle';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client = null)
    {
        $this->client = $client ?? HttpClient::create();
    }


    /**
     * @param string $article артикль продукта
     * @param string $apiKey API-ключ от http://api.tmparts.ru/
     * @return array список найденных брендов
     * @throws ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterfaceAlias ошибка запроса к api
     */
    function getProductListByArticle(string $article, string $apiKey): array
    {
        //Отправка запроса на получение списка брендов
        $response = $this->client->request('GET',
            ProductService::API_PATH . ProductService::STOCK_BY_ARTICLE_PATH,
            [
                'query' => [
                    //значение '2' для 'is_main_warehouse' нужно для поиска только товаров в наличие
                    'JSONparameter' => "{'Article': $article, 'is_main_warehouse': 2}",
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer $apiKey",
                ],
            ]
        );

        //Получение списка товаров
        $produceList = json_decode($response->getContent());

        //Заполнение результирующего списка
        $resultList = [];
        foreach ($produceList as $product) {
            foreach ($product->warehouse_offers as $warehouseOffer) {
                $resultList[] = [
                    'brand' => $product->brand,
                    'article' => $product->article,
                    'name' => $warehouseOffer->name,
                    'quantity' => $warehouseOffer->quantity,
                    'price' => 100 * $warehouseOffer->price,
                    'delivery_duration' => 24 * 60 * 60 * $warehouseOffer->delivery_period,
                    'vendorId' => $warehouseOffer->id,
                    'warehouseAlias' => $warehouseOffer->warehouse_code,
                ];
            }
        }

        return $resultList;
    }
}
