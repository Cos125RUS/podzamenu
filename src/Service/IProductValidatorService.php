<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface IProductValidatorService
{
    function findByArticleValidate(Request $request): array;
}
