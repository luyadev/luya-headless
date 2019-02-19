# Request Options

In order to customize the request you can define multiple filter options:

## Sort

Set a sort order for a given field.

```php
setSort(['id' => SORT_ASC]);
```

or the opposite way

```php
setSort(['id' => SORT_DESC]);
```

+ SORT_ASC = 1,2,3
+ SORT_DESC = 3,2,1

## Filter

The filters must be enabled on the api side, otherwise it wont have any effect.

Example usage assuming filters are configured on api:

```php
setFilter(['lang_id' => 1]); // like a where condition lang_id=1
```

greather then, smaller then operators:

```php
setFilter(['publication_date' => ['lt' => strtotime('tomorrow'), 'gt' => strtotime('yesterday')]);  //like >= and <= conditions for two fields.
```

Example using the in condition for both languages:

```php
setFilter([
    'lang_id' => ['in' => [1,2]]
]);
```

All posible operators:

+ and (AND)
+ or (OR)
+ not (NOT)
+ lt (<)
+ gt (>)
+ lte (<=)
+ gte (>=)
+ eq (=)
+ neq (!=)
+ in (IN)
+ nin (NOT IN)
+ like (LIKE)

## Conditions

combine two conditions with which are AND conditions:

```php
setFilter([
    'publication_date' => ['lt' => strtotime('tomorrow'), 'gt' => strtotime('yesterday')],
    'lang_id' => 2
]);
```

Two conditions but connected as OR condition:

```php
setFilter([
'or' => [
    ['lang_id' => 1],
    ['publication_date' => ['gt' => time()]],
]
])
```

@see https://www.yiiframework.com/doc/api/2.0/yii-data-datafilter