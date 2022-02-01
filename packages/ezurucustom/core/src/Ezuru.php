<?php

namespace EzuruCustom\Core;



class EzuruCustom {


    public function __construct() {
      
    }
 
    public function routes() {
        require __DIR__ . '/../routes/routes.php';
    }

}
