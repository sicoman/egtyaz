<?php 

namespace EzuruCustom\Core\Traits; 
use Illuminate\Support\Arr;

trait FlatParametersToObjs
{

    public $packedParams = [];
    public $params = [];

    public function convert($params) {
       $result = []; 
       $this->params = $params ; 
       $this->detectPagination($params, $result);
       $this->packedParams = $result;
       return $this;
    }

    public function mergeParams(){
        return array_merge($this->params, $this->packedParams);
    }

    public function packParam($elemArr, $objName = '', $moreThanOne = []){
        $slice = Arr::only($this->params, $elemArr);
        foreach($slice as $key => $_toDelete){  
            if(!in_array($key,$moreThanOne)){  
                unset($this->params[$key]);
            }
        }
        $this->packedParams[$objName] = $slice; 
        return $this;
    }

    public function detectPagination($params, &$result){
        $pagination = []; 
        if(isset($params['limit'])){  
            $pagination['limit'] = $params['limit'];
        }

        if(isset($params['page'])){
            $pagination['page'] = $params['page'];
        }
 
        if(count($pagination)){
            $result['pagination'] = $pagination;
            unset($this->params['limit']);
            unset($this->params['page']);
        }

    }

}