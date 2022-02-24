<?php
include_once('lib.php');


interface ISquare 
{
    public function squareArea(int $sideSquare);
    
}

interface ICircle
{
    public function circleArea(int $circumference);
}


class Square implements ISquare
{
    private $square;

    public function __construct(SquareAreaLib $square)
    {
        $this->square = $square;
    }


    public function squareArea(int $sideSquare)
    {
        $diagonal = sqrt(2)*$sideSquare;
        return round($this->square->getSquareArea($diagonal),2);
    }

}

class Circle implements ICircle 
{
    private $circle;

    public function __construct(CircleAreaLib $circle)
    {
        $this->circle = $circle;
    }

    public function circleArea(int $circumference)
    {
        $diagonal = $circumference/M_PI;
        return round($this->circle->getCircleArea($diagonal),2); 
    }
}

function testAdapter(int $data)
{
    $circle = new Circle(new CircleAreaLib());
    echo 'Площадь круга с длиной окружности '.$data.' см составит '.$circle->circleArea($data) .' см';

    
}

testAdapter(50);




?>