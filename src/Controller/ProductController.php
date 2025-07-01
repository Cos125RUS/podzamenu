<?php

namespace App\Controller;

use App\Service\IProductService;
use App\Service\IProductValidatorService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Контролер продуктовых запросов
 */
class ProductController
{
    /**
     * Запрос списка брендов по артиклю
     * @param Request $request данные запроса
     * @return JsonResponse ответ на запрос
     */
    #[Route('/api/product/find_by_article')]
    public function findByArticle(Request                 $request,
                                  IProductValidatorService $productValidatorService,
                                  IProductService          $productService): JsonResponse
    {
        try {
            //Проверка аргументов запроса
            [$article, $apiKey] = $productValidatorService->findByArticleValidate($request);

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
