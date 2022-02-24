<?php
// интерфейс для всех компонентов
interface IMessage {
    public function send(): string;
}

// начальный компонент без отправки
class WithoutSend implements IMessage 
{
    private $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function send():string
    { 
        return $this->text;
    }
}


// абстрактный декоратор
abstract class Decorator implements IMessage
{
    protected $content = null;

    public function __construct(IMessage $content)
    {
        $this->content = $content;
    }
}

// компонент-декоратор смс
class SMS extends Decorator
{
    public function send(): string 
    {
        return $this->content->send() . ' is send by SMS<br>';
    }
}

// компонент-декоратор email
class Email extends Decorator
{
    public function send(): string 
    {
        return $this->content->send() . ' is send by Email<br>';
    }
}

// компонент-декоратор chrome notifications
class CN extends Decorator 
{
    public function send() : string 
    {
        return $this->content->send(). 'is send  by Chrome Notification<br>';
    }
}


// клиентский код
function testDecorator(string $text)
{
    $messenger = 
        (new Email(
                new CN (
                    new SMS (
                        new WithoutSend($text)
                    )
                )
                
            )
            
        );
    echo $messenger->send();
}

testDecorator('Hello, world!');



?>