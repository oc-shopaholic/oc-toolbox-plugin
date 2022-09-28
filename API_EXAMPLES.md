# Примеры для экспериментальной ветки API с новым ElementCollectionType
ElementCollectionType: filter, sort, paginate

<a href="#request-example">Примеры запросов</a>
<a href="#filtering">Работа с фильтрами</a>
Добавление фильтра в свою коллекцию
Добавление сортировки в свою коллекцию
Расширение методов фильтрации чужой коллекции
Добавление фильтрации в чужую коллекцию, где она изначально не была реализована
Расширение методов сортировки чужой коллекции
Добавление сортировки в чужую коллекцию, где она изначально не была реализована
Описание пагинации

<a name="request-example"></a>
**Примеры запросов**

Запрос списка товаров с фильтрами, сортировкой и пагинацией:

```
query {
    productList
    (
        filter: {
            filterByBrand: 3,
            filterByCategory: {
                categoryIdList: [1, 2]
                includeChildren: true
            }
        }
        sort: SORT_PRICE_DESC
        paginate: {
            page: 1
            perPage: 10
        }
    )
    {
        list {
            id
            name
        }
        pageInfo {
            page
            perPage
            totalPages
            totalItems
            hasNextPage
            hasPreviousPage
        }
    }
}

```

Ответ:

```
{
    "data": {
        "productList": {
            "list": [
                {
                    "id": "8",
                    "name": "Run Swift FR-0"
                },
                {
                    "id": "12",
                    "name": "Free Metcon 3-0"
                },
                {
                    "id": "4",
                    "name": "Free TR 8-0"
                },
                {
                    "id": "5",
                    "name": "Free TF 8-0"
                },
                {
                    "id": "10",
                    "name": "Run Swift FE-0"
                }
            ],
            "pageInfo": {
                "page": 1,
                "perPage": 10,
                "totalPages": 3,
                "totalItems": 33,
                "hasNextPage": true,
                "hasPreviousPage": false
            }
        }
    }
}
```
<a name="#filtering"></a>
## Работа с фильтрами
### Добавление фильтров в GraphQL-тип своей коллекции

1. Создать свой FilterCollectionInputType.
   Пример:

   ```
   class FilterProductCollectionInputType extends FilterCollectionInputType
   {
       const TYPE_ALIAS = 'FilterProductCollectionInput';

       /** @var FilterProductCollectionInputType */
       protected static $instance;

       protected function getFilterConfig(): array
       {
           $arFieldList = [
               'filterByBrand' => [
                   'type' => Type::id(),
                   'description' => 'Apply filter by brand id'
               ],
               'filterByPromoBlock' => [
                   'type' => Type::id(),
                   'description' => 'Apply filter by promo block id'
               ],
           ];

           return $arFieldList;
       }
   }
   ```

2. Указать имя класса FilterCollectionInputType в константе FILTER_INPUT_TYPE_CLASS и добавить реализацию методов фильтрации, названия которых соответствуют полям в конфиге.
   Пример:

   ```
   /**
    * Class ProductCollectionType
    * @package Lovata\Shopaholic\Classes\Api\Collection
    */
   class ProductCollectionType extends AbstractCollectionType
   {
       const COLLECTION_CLASS = ProductCollection::class;
       const RELATED_ITEM_TYPE_CLASS = ProductItemType::class;
       const FILTER_INPUT_TYPE_CLASS = FilterProductCollectionInputType::class; // <<--
       const TYPE_ALIAS = 'productList';

       /** @var ProductCollectionType */
       protected static $instance;

       //
       // Filter methods
       //

       /**
        * Filter by brand ID
        * @param $iBrandId
        * @return void
        */
       protected function filterByBrand($iBrandId)
       {
           $this->obList->brand($iBrandId);
       }

       /**
        * Filter by promo block ID
        * @param $iPromoBlockId
        * @return void
        */
       protected function filterByPromoBlock($iPromoBlockId)
       {
           $this->obList->promoBlock($iPromoBlockId);
       }
   }
   ```

### Расширение чужого ElemetnCollectionType, добавление своего фильтра

1. Расширить чужой FilterCollectionInputType, используемый в требуемом ElementCollectionType.
    1.1.
    ```
    TODO
    ```

2. Расширить требуемый ElementCollectionType, добавить динамический метод, соответствующий названию нового фильтра, с реализацией необходимой логики.
    Пример:
    ```
    class ExtendProductCollectionType
    {
        public function subscribe()
        {
            ProductCollectionType::extend(function ($obProductCollectionType) {
                $obProductCollectionType->addDynamicMethod(
                    'random',
                    function ($iCount) use ($obProductCollectionType) {
                        $obList = $obProductCollectionType->getList();
                        $arIdList = array_keys($obList->random($iCount));
                        $obProductCollectionType->setList($obList->intersect($arIdList));
                    }
                );
            });
        }
    }
    // TODO: Это можно упростить, вынести в абстракцию
    ```

## Работа с сортировкой
**Замечание: необходимо учитывать, что методы сортировки не должны изменять количество элементов в итоговой коллекции, и могут влиять лишь на порядок входящих в неё элементов.**

### Добавление сортировки в свой ElementCollectionType

1. ElemetnCollectionType, в который добавляем сортировку коллекции, должен реализовать метод `sort()`. Имя метода можно переназначить в константе SORT_METHOD_NAME (устарело). Метод принимает
в качестве аргумента строку $sSortInput из ввода пользователя (допустимые значения сортировки перечисляются CollectionSortingEnumType, пример будет продемонстрирован ниже).
Логика сортировки должна быть реализована в ElementCollection.
    Пример:
    ```
    class ProductCollectionType extends AbstractCollectionType
    {
        const COLLECTION_CLASS = ProductCollection::class;
        const RELATED_ITEM_TYPE_CLASS = ProductItemType::class;
        const FILTER_INPUT_TYPE_CLASS = FilterProductCollectionInputType::class;
        const TYPE_ALIAS = 'productList';

        /**
        /* Sorting
        /*
        protected function sort($sSortInput) // <<--
        {
            $this->obList->sort($sSortInput);
        }
    }
    ```
2. Реализуем типизированный список допустимых значений сортировки CollectionSortingEnumType.
    Пример:
    ```
    class ProductCollectionSortingEnumType extends AbstractEnumType
    {
        const TYPE_ALIAS = 'ProductListSortingEnum';

        /** @var ProductCollectionSortingEnumType */
        protected static $instance;

        /**
         * Type config value list
         */
        protected function getValueList(): array
        {
            $arValueList = [
                'SORT_NO' => [
                    'value' => ProductListStore::SORT_NO,
                    'description' => 'Without sorting',
                ],
                'SORT_NEW' => [
                    'value' => ProductListStore::SORT_NEW,
                    'description' => 'Sort by new products',
                ],
                'SORT_PRICE_ASC' => [
                    'value' => ProductListStore::SORT_PRICE_ASC,
                    'description' => 'Sort by ascending price',
                ],
                'SORT_PRICE_DESC' => [
                    'value' => ProductListStore::SORT_PRICE_DESC,
                    'description' => 'Sort by descending price',
                ],
            ];

            return $arValueList;
        }

        /**
         * Type description
         */
        protected function getDescription(): string
        {
            return 'Enums for sorting product list';
        }
    }
    ```
3. Зададим имя класса GraphQL enum-типа для ввода значения сортировки в константе SORT_INPUT_ENUM_TYPE_CLASS ElementCollectionType.
    Пример:
    ```
    /**
     * Class ProductCollectionType
     * @package Lovata\Shopaholic\Classes\Api\Collection
     */
    class ProductCollectionType extends AbstractCollectionType
    {
        const COLLECTION_CLASS = ProductCollection::class;
        const RELATED_ITEM_TYPE_CLASS = ProductItemType::class;
        const SORT_INPUT_ENUM_TYPE_CLASS = ProductCollectionSortingEnumType::class; // <<--
        const TYPE_ALIAS = 'productList';

        //...
    }
    ```

### Добавление сортировки в чужой ElementCollectionType
1. Создать свой плагин.
2. Расширить требуемый ElementCollectionType.
    2.1. Если в расширяемом ElementCollectionType уже реализована сортировка, нужно расширить ElementCollection, добавив свои способы сортировки, и расширить релевантный
CollectionSortingEnumType cвоими enum-значениями по стандартной инструкции расширения типа.
    2.2. Если в расширяемом ElementCollectionType сортировка не реализована, ... TODO
