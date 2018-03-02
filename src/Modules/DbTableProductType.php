<?php
namespace ThirdParty\DbTableProductType\Modules;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class DbTableProductType extends BaseModule {

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_db-table-product-type:default');
        return $this->adapter->lexicon('commerce_db-table-product-type');
    }

    public function getAuthor()
    {
        return 'Tony Klapatch';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_db-table-product-type.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_db-table-product-type:default');

        // Add the xPDO package, so Commerce can detect the derivative classes
        $root = dirname(dirname(__DIR__));
        $path = $root . '/model/';
        $this->adapter->loadPackage('commerce_db-table-product-type', $path);
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields = [];

        return $fields;
    }
}
