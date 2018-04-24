# Commerce_DbTableProductType
A simple Modmore Commerce module to use an extended package. Easy to use with MIGXDB. You must own Modmore's Commerce to use https://www.modmore.com/commerce/

## Setup

1. Install the package to your MODX installation with Commerce by installing the transport package here (upload package in installer) https://github.com/tonyklapatch/Commerce_DbTableProductType/releases/
2. Configure the module's system settings, located under the commerce_db-table-product-type namespace, for your table. All the options are described below.

## Options

| Option | Description |
| --- | --- |
| commerce_db-table-product-type.classpkg_name | Name of the extended package you want to use to get data from. |
| commerce_db-table-product-type.deliverytype_col | Delivery type column to read, uses Commerce ID of the delivery type |
| commerce_db-table-product-type.description_col | Description column to read |
| commerce_db-table-product-type.name_col | Name column to read |
| commerce_db-table-product-type.price_col | Price column to read (this uses the option commerce.resourceproduct.price_field_decimals as well) |
| commerce_db-table-product-type.sku_col | SKU column to read |
| commerce_db-table-product-type.stock_col | Stock column to read |
| commerce_db-table-product-type.taxgroup_col | Taxgroup column to read, uses Commerce ID of the tax group |
| commerce_db-table-product-type.weight_col | Weight column to read |
| commerce_db-table-product-type.weight_unit_col | Unit of weight for the product to read. Defaults to system default if not set. |
| commerce_db-table-product-type.resource_col | If attaching products to resources, this is the column that points to the resource to read from. Leave blank if not. |
