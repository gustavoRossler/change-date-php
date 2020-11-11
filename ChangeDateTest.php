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
        for ($i = 0; $i < 100; $i++) {
            $minutesToAdd = random_int(1, 525600);
            $genericDate = new GenericDate();
            $newDate = $genericDate->changeDate('01/01/2007 00:00', '+', $minutesToAdd);
            $newtimestamp = date('d/m/Y H:i', strtotime('2007-01-01 00:00 + ' . $minutesToAdd . ' minute'));
            $this->assertEquals($newDate, $newtimestamp);
        }

        for ($i = 0; $i < 100; $i++) {
            $minutesToAdd = random_int(1, 525600);
            $genericDate = new GenericDate();
            $newDate = $genericDate->changeDate('01/01/2007 23:59', '-', $minutesToAdd);
            $newtimestamp = date('d/m/Y H:i', strtotime('2007-01-01 23:59 - ' . $minutesToAdd . ' minute'));
            $this->assertEquals($newDate, $newtimestamp);
        }
    }
}
