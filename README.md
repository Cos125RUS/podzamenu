ТЗ:
Используя фрейворк Symfony и документацию поставщика по ссылке http://api.tmparts.ru/ , реализовать возможность поиска товара по артикулу.

Входные данные: ["Article" => "искомый артикул товара", "api_key" => "строковое значение"]

Выходные данные:

[
[
"brand" => "Бренд производителя",
"article" => "Артикул товара",
"name" => "Название товара",
"quantity" => "Количество на складе",
"price" => "Стоимость товара в копейках",
"delivery_duration" => "Время доставки со склада в секундах",
"vendorId" => "Код товара",
"warehouseAlias" => "Код склада",
]
]


* Ручка запроса - /api/product/find_by_article
* В папке tests есть тест сервиса
