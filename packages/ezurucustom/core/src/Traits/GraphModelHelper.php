<?php 

namespace EzuruCustom\Core\Traits; 

trait GraphModelHelper{

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

}