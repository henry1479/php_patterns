<?php


// класс Магазина носков
class SoxSite
{
    private $orderSum;
    private $userInfo;
    private $paySystem;

    public function setOrderSum(int $sum) 
    {
        $this->orderSum = $sum;
    }
    public function getOrderSum()
    {
        return $this->orderSum;
    }

    
    public function setUserInfo(string $telNumber) 
    {
        $this->userInfo = $telNumber;
    }
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function setPaySystem(IPaySystem $system) 
    {
        $this->paySystem = $system;
    }
    public function getPaySystem()
    {
        return $this->paySystem;
    }


}


// интерфейс платежной системы
interface IPaySystem
{
    public function pay(int $sum, string $telNumber);
} 

// класс оплаты через Киви
class Qiwi implements IPaySystem
{
    public function pay(int $sum, string $telNumber) 
    {
        echo "Оплата {$sum} прошла по системе Qiwi от пользователя с номером телефона {$telNumber}";
    }   
}
// класс оплаты через Яндекс
class Yandex implements IPaySystem 
{
    public function pay(int $sum, string $telNumber) 
    {
        echo "Оплата {$sum} прошла по системе Yandex от пользователя с номером телефона {$telNumber}";
    }   
}


// класс агрегатора платежей
class PayHub
{   
    
    public function getPay(SoxSite $order)
    {
        return $order->getPaySystem()->pay($order->getOrderSum(), $order->getUserInfo());
    }
}

// клиентский код

function testSoxStrategy(SoxSite $site)
{
    $site->setUserInfo("+123345678");
    $site->setPaySystem(new Qiwi);
    $site->setOrderSum(300);
    $hub = new PayHub();
    $hub->getPay($site);  
}


testSoxStrategy(new SoxSite());
?>