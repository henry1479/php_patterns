<?php

class Provider 
{
    // получаем объект итератора директории
    private static function getIterator (string $dir):DirectoryIterator 
    {
        return new DirectoryIterator($dir);
    }

    // выводит содержание директории в виде списка
    private function getDirectoryContent(string $dir):void
    {
       $iterator = $this->getIterator($dir);
       echo $dir;
       echo "<ul>";
        foreach ($iterator as $item)
        {
            if ($iterator->isDot()) continue;
            if ($iterator->isDir()){
                $path = $item->getPath();
                echo "<li><a href=\"{$_SERVER['PHP_SELF']}?dir=$path/$item\">". $item ."</a></li>".  PHP_EOL;
            } else {
                echo "<li>{$item}</li>";
            }
            
        }
        
        echo "</ul>";
        

    }


    // функционал проводника
    public function provide():void
    {
       if($_GET['dir']){
           echo " ";
           $this->getDirectoryContent($_GET['dir']);
       } else {
           $this->getDirectoryContent('C:/OpenServer');
       } 
    }
    
}

$provider = new Provider;

$provider->provide();



// поиск элемента массива с известным индексом - O(f(n)),
// дублирование массива через foreach - O(f(n)),
// рекурсивная функция нахождения факториала числа - O(f(n)). 
// 3. Определить сложность следующих алгоритмов. 
// Сколько произойдет итераций? 




// O(f(i * j)), 700 итерраций
$n = 100; 
$array=[];


for ($i = 0; $i < $n; $i++) {
    for ($j = 1; $j < $n; $j *= 2) {
        $array[$i][$j] = true;
    } 
}




// O(f(i * j)), 2551 итеррация
$n = 100;
$array[] = [];

for ($i = 0; $i < $n; $i += 2) {
    for ($j = $i; $j < $n; $j++) {
        $array[$i][$j] = true;

    } 
}
?>


