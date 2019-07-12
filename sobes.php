<?php

class A {
    const X = 1;

    public function f1()
    {
        return static::X;
    }

    public function f2()
    {
        return self::X;
    }
}

class B extends A {
    const X = 2;
}

$b = new B;
echo $b->f1();
echo $b->f2();

