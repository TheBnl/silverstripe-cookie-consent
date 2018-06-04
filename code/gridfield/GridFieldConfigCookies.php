<?php
namespace Broarm\CookieConsent;

use GridFieldAddNewInlineButton;
use GridFieldConfig;
use GridFieldDeleteAction;
use GridFieldEditableColumns;
use GridFieldFilterHeader;
use GridFieldSortableHeader;
use GridFieldToolbarHeader;

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
