<?php
use modmore\Commerce\Admin\Widgets\Form\NumberField;
use modmore\Commerce\Products\Weight;
use modmore\Commerce\Products\Weight\Grams;
use PhpUnitsOfMeasure\PhysicalQuantity\Mass;

/**
 * DbTableProductType for Commerce.
 *
 * Copyright 2018 by Tony Klapatch <tony@klapatch.net>
 *
 * This file is meant to be used with Commerce by modmore. A valid Commerce license is required.
 *
 * @package commerce_db-table-product-type
 * @license See core/components/commerce_db-table-product-type/docs/license.txt
 */
class DbTableProductTypeProduct extends comProduct {

    protected $_target = false;
    protected $_deferSave = false;

    /**
     * Grabs the SKU for the product.
     *
     * @return mixed|string
     */
    public function getSku()
    {
        $sku = $this->get('sku');
        if ($target = $this->getTarget()) {
            $sku = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.sku_col'));
            $this->set('sku', $sku);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        return $sku;
    }

    /**
     * Grabs the name (for in the cart/order) for the product.
     *
     * @return mixed|string
     */
    public function getName()
    {
        $name = $this->get('name');
        if ($target = $this->getTarget()) {
            $name = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.name_col'));
            $this->set('name', $name);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        return $name;
    }

    /**
     * Grabs the description for the product.
     *
     * @return mixed|string
     */
    public function getDescription()
    {
        $description = $this->get('description');
        if ($target = $this->getTarget()) {
            $description = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.description_col'));
            $this->set('description', $description);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        return $description;
    }

    /**
     * Grabs the price for the product.
     *
     * @return \modmore\Commerce\Products\Price
     */
    public function getPrice()
    {
        $value = $this->get('price');
        if ($target = $this->getTarget()) {
            $value = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.price_col'));
            if ($this->adapter->getOption('commerce.resourceproduct.price_field_decimals', null, true)) {
                $units = $this->commerce->currency->get('subunits');
                $value = (float)str_replace(',', '.', $value);
                $value = (int)round($value * pow(10, $units));
            }
            $this->set('price', $value);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        $price = new \modmore\Commerce\Products\Price($this->commerce);
        $price->set($value, $this->commerce->currency);
        return $price;
    }

    /**
     * Returns the current stock level
     *
     * @return int
     */
    public function getStock()
    {
        $stock = $this->get('stock');
        if ($target = $this->getTarget()) {
            $stock = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.stock_col'));
            $this->set('stock', $stock);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        return $stock;
    }


    /**
     * Returns the current weight level
     *
     * @return Mass|null
     */
    public function getWeight()
    {
        if ($target = $this->getTarget()) {
            // Get the weight
            $value = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.weight_col'));
            $this->set('weight', $value);

            if (!empty($this->commerce->getOption('commerce_db-table-product-type.weight_unit_col'))) {
                $unit = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.weight_unit_col'));
            }

            if (empty($unit)) {
                $unit = $this->adapter->getOption('commerce.default_weight_unit', null, 'kg', true);
            }

            $this->set('weight_unit', $unit);
            if (!$this->_deferSave) {
                $this->save();
            }
        }

        $value = $this->get('weight');
        $unit = $this->get('weight_unit');
        if (!empty($unit)) {
            try {
                return new Mass($value, $unit);
            } catch (Exception $e) { }
        }
        return null;
    }

    /**
     * Returns the tax group assigned to the product. This will fall back to the default_tax_group if available.
     *
     * @return comTaxGroup|false
     */
    public function getTaxGroup()
    {
        if ($this->taxGroup) {
            return $this->taxGroup;
        }
        if ($target = $this->getTarget()) {
            $id = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.taxgroup_col'));

            if ($id < 1) {
                $id = (int)$this->adapter->getOption('commerce.default_tax_group');
            }
            $this->set('tax_group', $id);
            if (!$this->_deferSave) {
                $this->save();
            }
        }
        return parent::getTaxGroup();
    }

    /**
     * Returns the delivery type for the product, or false if there are none.
     *
     * @return comDeliveryType|false
     */
    public function getDeliveryType()
    {
        if ($this->deliveryType) {
            return $this->deliveryType;
        }

        if ($target = $this->getTarget()) {
            $id = $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.deliverytype_col'));
            $this->set('delivery_type', $id);
            if (!$this->_deferSave) {
                $this->save();
            }
        }

        return parent::getDeliveryType();
    }

    /**
     * Updates the product stock (e.g. when an order was paid or new stock was received).
     *
     * @param int $quantitySold
     * @param int $quantitySupplied
     * @return int
     */
    public function updateStock($quantitySold = 0, $quantitySupplied = 0)
    {
        $stock = parent::updateStock($quantitySold, $quantitySupplied);

        if ($target = $this->getTarget()) {
            $target->set($this->commerce->getOption('commerce_db-table-product-type.stock_col'), $stock);
            $this->save();
        }

        return $stock;
    }

    /**
     * For extended Product classes, getTarget would load the related target object.
     *
     * @return mixed
     */
    public function getTarget()
    {
        if (!$this->_target) {
            $this->_target = $this->adapter->getObject($this->commerce->getOption('commerce_db-table-product-type.classpkg_name'), $this->get('id'));
        }
        return $this->_target;
    }

    /**
     * @param extendedpkg $target
     * @param string $field
     * @return mixed
     */
    public function getTargetField($target, $field)
    {
        return $target->get($field);
    }

    /**
     * @param extendedpkg $target
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    public function setTargetField($target, $field, $value)
    {
        return $target->set($field, $value);
    }

    /**
     * Returns the link for viewing the product in the frontend. This
     * will only return if the resource id column is set in system settings.
     *   
     * @return bool|string
     */
    public function getLink()
    {
        if ($target = $this->getTarget()) {
            if (!empty($this->commerce->getOption('commerce_db-table-product-type.resource_col'))) {
                return $this->adapter->makeResourceUrl($this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.resource_col')), '', array(), 'full');
            }   
        }
        return false;
    }

    /**
     * Returns the link for _editing_ the product in the manager. Can return false
     * if there is no way to edit the product in the manager.
     *
     * @return bool|string
     */
    public function getEditLink()
    {
        return false;
    }

    /**
     * Returns the link for editing the product information in the catalog, wherever that may be.
     * This is set if you have the resource id column set in system settings.
     *
     * @return bool|string
     */
    public function getEditCatalogLink()
    {
        if ($target = $this->getTarget()) {
            if (!empty($this->commerce->getOption('commerce_db-table-product-type.resource_col'))) {
                $url = $this->adapter->getOption('manager_url');
                $url .= '?a=resource/update';
                $url .= '&id=' . $this->getTargetField($target, $this->commerce->getOption('commerce_db-table-product-type.resource_col'));
                return $url;
            }
        }
        return false;
    }

    public function synchronise()
    {
        $this->_deferSave = true;
        $this->getSku();
        $this->getPrice();
        $this->getName();
        $this->getDescription();
        $this->getWeight();
        $this->getStock();
        $this->_deferSave = false;
        return $this->save();
    }
}