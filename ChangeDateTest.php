<?php

require 'GenericDate.php';

use PHPUnit\Framework\TestCase;

final class ChangeDateTest extends TestCase
{

    public function testInvalidOperator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid operator, must be + or -');
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate('06/11/2020 15:13', '', 123);
    }

    public function testInvalidDatePattern(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date pattern, must be dd/MM/yyyy HH24:mi');
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate('06/11/2020 15', '+', 123);
    }

    public function testInvalidDateDay(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate('55/11/2020 15', '+', 123);
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate('55/11/2020 15', '+', '');
    }

    public function testInvalidDateMonth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate('01/13/2020 15', '+', 123);
    }

    public function testChangeDate(): void
    {
        $date = '06/11/2020 15:30';
        $genericDate = new GenericDate();
        $newDate = $genericDate->changeDate($date, '+', 0);
        $this->assertEquals($date, $newDate);
    }
}
