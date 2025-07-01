<?php

namespace App\Controller;

use App\Request\ProductRequest;
use App\Service\IProductService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

/**
 * Контролер продуктовых запросов
 */
class ProductController
{
    /**
     * Запрос списка брендов по артиклю
     * @param ProductRequest $request данные запроса
     * @return JsonResponse ответ на запрос
     */
    #[Route('/api/product/find_by_article')]
    public function findByArticle(#[MapRequestPayload] ProductRequest $request,
                                  IProductService                     $productService): JsonResponse
    {
        try {
            //Получение аргументов запроса
            $article = $request->article;
            $apiKey = $request->apiKey;

            //Получение списка товаров от API
            $resultList = $productService->getProductListByArticle($article, $apiKey);
            return new JsonResponse($resultList);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            //Ошибка запроса к API
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_GATEWAY);
        } catch (Exception $e) {
            //Неверные аргументы запроса
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
