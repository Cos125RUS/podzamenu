<?php

namespace App\Tests\Service;

use App\Service\ProductService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ProductServiceTest extends TestCase
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testGetProductListByArticle()
    {
        $testArticle = 'TEST123';
        $testApiKey = 'abra-kadabra';
        $mockRequestApiDataFileContent = file_get_contents(__DIR__ . '/requestApiData.json');
        $resultDataFileContent = file_get_contents(__DIR__ . '/resultData.json');
        $expectedResponse = json_decode($resultDataFileContent, true);
        $mockResponse = new MockResponse(
            $mockRequestApiDataFileContent,
            [
                'http_code' => 200,
                'response_headers' => [
                    'Content-Type: application/json',
                    'Authorization' => "Bearer $testApiKey",
                ],
            ]
        );
        $mockHttpClient = new MockHttpClient($mockResponse);
        $productService = new ProductService($mockHttpClient);

        $result = $productService->getProductListByArticle($testArticle, $testApiKey);
        $result = json_decode(json_encode($result), true);

        $this->assertEquals($expectedResponse, $result);
        $requestOptions = $mockResponse->getRequestOptions();
        $this->assertEquals(
            "{'Article': $testArticle, 'is_main_warehouse': 2}",
            $requestOptions['query']['JSONparameter']
        );
    }
}
