<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Сервис валидации ProductController
 */
class ProductValidatorService implements IProductValidatorService
{
    /**
     * Проверка параметров запроса
     * @param Request $request данные запроса
     * @throws Exception
     */
    public function findByArticleValidate(Request $request): array
    {
        [
            'Article' => $article,
            'api_key' => $apiKey
        ] = json_decode($request->getContent(), true);

        if (!$article) {
            throw new Exception('Артикль не передан');
        }

        if (!$apiKey) {
            throw new Exception('API-ключ не передан');
        }

        return [$article, $apiKey];
    }

}
