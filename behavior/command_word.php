<?php
// Команда: вы — разработчик продукта Macrosoft World. Это текстовый редактор с возможностями копирования, вырезания и вставки текста (пока только это). Необходимо реализовать механизм по логированию этих операций и возможностью отмены и возврата 
// действий. Т. е., в ходе работы программы вы открываете текстовый файл .txt, выделяете участок кода (два значения: начало и конец) и выбираете, что с этим кодом делать.


// интерфейс для команд редактора
interface ITextActions {
    public function action(string $data);
}

// копировать
class CopyText implements ITextActions
{
    private $start;
    private $end;

    public function  __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function action(string $data)
    {
        ob_start();
        $substr = substr($data, $this->start, $this->end-$this->start);
        echo $substr;
        return '';
  
    }
}
// вырезать
class CutText implements ITextActions
{

    private $start;
    private $end;

    public function  __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function action(string $data)
    {
        ob_start();
        $substr = mb_strcut($data, $this->start, $this->end-$this->start);
        echo $substr;
        return '';
      
    }
}

// вставить 
// реализовано с помощью буфера
class PasteText implements ITextActions
{
    private $start;


    public function  __construct($start)
    {
        $this->start = $start;

    }
    public function action(string $data)
    {   
        $newData = ob_get_contents();
        $substr = substr_replace($data, $newData, $this->start, 0);
        ob_end_clean();
        echo "Запись в файл информации {$newData} выполнена";
        return $substr;
    }
}

// интерфейс исполнителя команд
interface Icommand
{
    public function execute($file);
}

// класс испонителя команд
class Distributor implements ICommand 
{
    private $textAction;

    public function __construct(ITextActions $action)
    {
        $this->textAction = $action;
    }

    public function execute($file)
    {
        $data = fgets($file);
        $newData = $this->textAction->action($data);
        if ($this->textAction instanceof PasteText) {
            ftruncate($file, 0);
            fputs($file, $newData);
        }
        
        
    }
}
// класс редактора
class Redactor 
{

    private $file;
    
    public function __construct(string $file)
    {
        $this->file = $file;

    }


    public function getAction(Icommand $command)
    {
        $openFile = fopen($this->file,'a+');
        $command->execute($openFile);
        fclose($openFile);
    }
}

// клиентский код
function testCommand($file)
{
    $cut = new CutText(0,1);
    $cutDistributor = new Distributor($cut);

    $paste = new PasteText(6);
    $pasteDistributor = new Distributor($paste);

    $redactor = new Redactor($file);
    $redactor->getAction($cutDistributor);
    $redactor->getAction($pasteDistributor);
}

testCommand('./test.txt')
?>