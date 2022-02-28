<?php 

// интерфейс наблюдаемого
interface Observable {
    public function addObserver(Employee $observer);
    public function removeObserver(Employee $observer);
    public function notify();
}

// класс биржи труда с реализацией интерфейса набдюдаемого
class HandHunter implements Observable
{
    private $observers = [];
    private $vacancies = [];



    public function setVacancy(string $vacancy) {
        $this->vacancies[]=$vacancy;
    }

    protected static function sendEmail(string $employee, string $email):void{
        echo "Отправляем уведомление о вакансии {$employee} на {$email}<br/>";
    }

    public function addObserver(Employee $observer)
    {
        $this->observers[] = $observer;
        echo "{$observer->getName()} добавлен на биржу труда<br/>";   
    }


    public function removeObserver(Employee $observer)
    {
        foreach($this->observers as $obsrv)
        {
            if($obsrv == $observer){
                unset($this->observers[array_search($obsrv, $this->observers)]);
            }
        }
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            if(in_array('php', $this->vacancies)){
                $observer->subscribe('php');
                self::sendEmail($observer->getName(),$observer->getEmail());
            }
            
        }
    }

}



// интерфейс наблюдателей
interface Employee {
    public function getName():string;
    public function getEmail():string;
    public function getExpirience(): int;
    public function subscribe(string $vacancy): void;
    public function unsubscribe(Observable $site): void;
}

// класс наблюдателя-программиста Джона
class John implements Employee
{
    private $name = "John";
    private $email = "john@gmail.com";
    private $expirience = 12;

    public function getName():string
    {
        return $this->name;
    }
    public function getEmail():string
    {
        return $this->email;
    }
    public function getExpirience(): int
    {
        return $this->expirience;
    }

    public function subscribe(string $vacancy):void
    {
        echo "Джон подписывается на вакансию {$vacancy} <br/>";
    }

    public function unsubscribe(Observable $site):void
    {
        $site->removeObserver($this);
        echo "{$this->name} уходит с биржи труда<br/>";
    }


}

// класс наблюдателя-программиста Терри
class Terry implements Employee
{
    private $name = "Terry";
    private $email = "terry@yahoo.com";
    private $expirience = 9;

    public function getName():string
    {
        return $this->name;
    }
    public function getEmail():string
    {
        return $this->email;
    }
    public function getExpirience(): int
    {
        return $this->expirience;
    }
    
    public function subscribe(string $vacancy):void
    {
        echo "Террии подписывается на вакансию {$vacancy}<br/>";
    }

    public function unsubscribe(Observable $site): void
    {
        $site->removeObserver($this);
        echo "{$this->name} уходит с биржи труда<br/>";
    }
}


// клиентский код
function testHandHunter () {

    $site = new HandHunter();
    $site -> setVacancy('php');
    $site-> setVacancy('react');


    $john = new John();
    $terry = new Terry();
    


    $site->addObserver($john);
    $site->addObserver($terry);
    $john->unsubscribe($site);


  

    $site->notify();
    
}


testHandHunter();



?>