<?php
namespace Broarm\CookieConsent\Gridfield;

use Symbiote\GridFieldExtensions\GridFieldAddNewInlineButton;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;

/**
 * Class GridFieldConfigCookies
 *
 * @author Bram de Leeuw
 */
class GridFieldConfigCookies extends GridFieldConfig
{
    public function __construct()
    {
        parent::__construct();
        $this->addComponent(new GridFieldToolbarHeader());
        $this->addComponent(new GridFieldButtonRow('before'));
        $this->addComponent($sort = new GridFieldSortableHeader());
        $this->addComponent($filter = new GridFieldFilterHeader());
        $this->addComponent(new GridFieldEditableColumns());
        $this->addComponent(new GridFieldDeleteAction());
        $this->addComponent(new GridFieldAddNewInlineButton('toolbar-header-right'));

        $sort->setThrowExceptionOnBadDataType(false);
        $filter->setThrowExceptionOnBadDataType(false);

        $this->extend('updateConfig');
    }
}
