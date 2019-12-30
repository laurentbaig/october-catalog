# october-catalog

Catalog for OctoberCMS.

Simple catalog for OctoberCMS. Category model is a nested tree and is reorderable.

Components return Collections and Eloquent Models for ease of use in exploring the
build of the theme pages. No assumptions are made on display of the catalog.

## Categories

To show categories, include the component CategoryList on your page. CategoryList
requires no parameters.
```
...
[CategoryList]
==
```

Then, in twig template, to get the Root Category collection,
```
{% set obCategoryList = CategoryList.getRootCategories() %}
{% if obCategoryList.isNotEmpty() %}
<ul>
    {% for obCategory in obCategoryList if obCategory.nestedCount > 0 %}
    <li>{{ obCategory.name }} has {{ obCategory.productCount }} local product(s)</li>
    {% endfor %}
</ul>
{% endif %}
```

We can get the subcategories of any category by 
```
{{ obCateogry.subcategories }}
```
which is a collection of Category Model.

We can use CategoryItem to get a single Category from url slug
```
title = "Category Page"
url = "/category/:slug"

[CategoryItem]
slug = "{{ :slug }}"
==
{% set obCategory = CategoryItem.get() %}
{{ }}
```

## Products
We can get all products for a category with ProductList. ProductList requires no
parameters.
```
{% set obProductList = ProductList.get(obCategory) %}
{% if obProductList.isNotEmpty() %}
<ul>
    {% for obProduct in obProductList %}
	<li>{{ obProduct.name }}</li>
	{% endfor %}
</ul>
{% else %}
<p>No products.</p>
{% endif %}
```


