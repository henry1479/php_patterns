<?php
class IdentityMap {
    // массив кэша
    private $identityMap = [];
    
    /**
     * Добавляет значение в кэш 
     * @param array $ids
     * @param mixed $obj
     * @return array
     */
    public function add($obj, $id)
    {
        if(!isset($this->identityMap[$id])){
            $this->identityMap[$id] = $obj;
        }
    }
    
    /**
     * Ищет есть ли значение в кэше
     * @param array $ids
     * @param mixed $obj
     * @param string $key
     * 
     * @return array
     */
    public function find(array $ids, mixed $obj, string $key='id')
    {
        // проходимся по $ids
        foreach ($ids as $id)
        {
            // $id есть в кэше в качестве ключа продукта
            if(array_key_exists($id,$this->identityMap)){
                // берем продукт из кэша
                $productListFromCash[] = $this->identityMap[$id];  
            } else {
                // иначе берем продукт из репозитория
                $productListFromRepo[] = $obj->getDataFromSource(['id'=>$id]);
                // добавляем продукт из репозитория к кэш
                $this->add($obj->getDataFromSource([$key=>$id]),$id);
            }
            
        }
        // объединяем продукты из кэша и репозитория 
        $productList = array_merge($productListFromCash,$productListFromRepo);
        // возвращаем полученное
        return $productList;
        
    }
}




?>