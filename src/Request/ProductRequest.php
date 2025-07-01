<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class ProductRequest
{
    #[Assert\NotBlank(message: "Не передан Article")]
    #[Assert\Type(type: 'string', message: "Article должен быть передан в виде строки")]
    #[Assert\Length(min: 3, max: 20, minMessage: "Article должен быть не меньше 3 символов",
        maxMessage: "Article должен быть не меньше 20 символов")]
    #[SerializedName('Article')]
    public string $article;

    #[Assert\NotBlank(message: "Не передан api_key")]
    #[Assert\Type(type: 'string', message: "api_key должен быть передан в виде строки")]
    #[SerializedName('api_key')]
    public string $apiKey;
}
