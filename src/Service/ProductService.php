<?php

namespace App\Service;

use App\Model\ProductDTO;
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
     * @return array список найденных товаров
     * @throws ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterfaceAlias ошибка запроса к api
     */
    function getProductListByArticle(string $article, string $apiKey): array
    {
        //Получение списка товаров
        $produceList = $this->apiRequest($article, $apiKey);

        //Заполнение результирующего списка
        return $this->getResultList($produceList);
    }

    /**
     * Отправка запроса к API
     * @throws ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterfaceAlias ошибка запроса к api
     */
    private function apiRequest(string $article, string $apiKey): array
    {
        //Отправка запроса на получение списка товаров
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
        return json_decode($response->getContent());
    }

    /**
     * Формирование результирующего списка
     * @param array $produceList список товаров, полученный от API
     * @return array
     */
    private function getResultList(array $produceList): array
    {
        //Заполнение результирующего списка
        $resultList = [];
        foreach ($produceList as $product) {
            $brand = $product->brand;
            $article = $product->article;

            foreach ($product->warehouse_offers as $warehouseOffer) {
                $resultList[] = new ProductDTO($brand, $article, $warehouseOffer);
            }
        }

        return $resultList;
    }
}
