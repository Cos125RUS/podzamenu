<?php

namespace App\Service;

interface IProductService
{
    function getProductListByArticle(string $article, string $apiKey): array;
}
