<?php

class GenericDate
{
    protected $minutes;
    protected $hours;
    protected $days;
    protected $months;
    protected $years;
    protected $op;
    protected $value;
    protected $date;

    // 30 dias = 43200 min
    // 31 dias = 44640 min
    // 28 dias = 40320

    public function getTotalMonthDays($month)
    {
        $month *= 1; // to int

        if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
            return 31;
        else if ($month == 4 || $month == 6 || $month == 9 || $month == 11)
            return 30;
        else if ($month == 2)
            return 28;

        throw new Exception('Invalid month: ' . $month);
    }

    public function addMinutesToDate()
    {
        if (($this->value + $this->minutes) / 60 >= 1) {
            $hoursToSum = floor(($this->value + $this->minutes) / 60);
            $this->minutes = (($this->value + $this->minutes) % 60);

            if ($this->minutes >= 60) {
                $this->minutes = 0;
                $hoursToSum += 1;
            }

            if (($hoursToSum + $this->hours) >= 24) {
                $daysToSum = floor(($hoursToSum + $this->hours) / 24);
                
                $this->hours = ($hoursToSum + $this->hours) % 24;

                if (($daysToSum + $this->days) > $this->getTotalMonthDays($this->months)) {

                    $monthsToSum = 0;
                    $yearsToSum = 0;
                    $currentMonth = $this->months;
                    $remainingDays = ($daysToSum + $this->days);
                    while ($remainingDays > $this->getTotalMonthDays($currentMonth)) {
                       
                        $monthDays = $this->getTotalMonthDays($currentMonth);
                        $remainingDays -= $monthDays;
                        $currentMonth++;
                        $monthsToSum++;
                        if ($currentMonth > 12) {
                            $currentMonth = 1;
                            $this->months = 1;
                            $monthsToSum = 0;
                            $yearsToSum++;
                        }
                    }

                    $this->days = $remainingDays;
                    $this->months += $monthsToSum;
                    $this->years += $yearsToSum;
                } else {
                    $this->days = ($daysToSum + $this->days);
                }
            } else {
                $this->hours = ($hoursToSum + $this->hours);
            }
        } else {
            $this->minutes = ($this->value + $this->minutes);
        }
    }

    public function subtractMinutesFromDate()
    {
        if (($this->minutes - $this->value) <= 0) {
            $hoursToSub = floor((60 - ($this->minutes - $this->value)) / 60);
            $this->minutes = (($this->minutes - $this->value) % 60);
            if ($this->minutes < 0) {
                $this->minutes *= -1;
            }

            $this->minutes = 60 - $this->minutes;

            if ($this->minutes >= 60) {
                $this->minutes = 0;
                $hoursToSub -= 1;
            }

            if (($this->hours - $hoursToSub) < 0) {

                $daysToSub = floor((24 - ($this->hours - $hoursToSub)) / 24);
                $this->hours = ($this->hours - $hoursToSub) % 24;
                if ($this->hours < 0) {
                    $this->hours *= -1;
                }

                $this->hours = 24 - $this->hours;

                if ($this->hours == 24) {
                    $this->hours = 0;
                    $daysToSub -= 1;
                }

                if (($this->days - $daysToSub) <= 0) {

                    $monthsToSub = 0;
                    $yearsToSub = 0;
                    $currentMonth = $this->months;

                    $remainingDays = ($this->days - $daysToSub);

                    while ($remainingDays <= 0) {

                        $currentMonth--;
                        $monthsToSub++;

                        if ($currentMonth < 1) {
                            $currentMonth = 12;
                            $this->months = 12;
                            $monthsToSub = 0;
                            $yearsToSub++;
                        }

                        $monthDays = $this->getTotalMonthDays($currentMonth);
                        $remainingDays += $monthDays;
                    }

                    $this->days = $remainingDays;
                    $this->months -= $monthsToSub;
                    $this->years -= $yearsToSub;
                } else {
                    $this->days -= $daysToSub;
                }
            } else {
                $this->hours -= $hoursToSub;
            }
        } else {
            $this->minutes -= $this->value;
        }
    }

    public function getFormattedNumber($number)
    {
        return str_pad($number, 2, '0', STR_PAD_LEFT);
    }

    public function getFormattedDate()
    {
        return
            $this->getFormattedNumber($this->days) . '/' .
            $this->getFormattedNumber($this->months) . '/' .
            $this->years . ' ' .
            $this->getFormattedNumber($this->hours) . ':' .
            $this->getFormattedNumber($this->minutes);
    }

    public function validateInputData()
    {
        if ($this->op != '+' && $this->op != '-') {
            throw new InvalidArgumentException('Invalid operator, must be + or -');
        }

        $datePattern = '/\d\d\/\d\d\/\d\d\d\d \d\d:\d\d/';
        if (!preg_match($datePattern, $this->date)) {
            throw new InvalidArgumentException('Invalid date pattern, must be dd/MM/yyyy HH24:mi');
        }

        if ($this->days <= 0) {
            throw new InvalidArgumentException('Invalid date, the day must be a positive number');
        }
        if ($this->months <= 0) {
            throw new InvalidArgumentException('Invalid date, the month must be a positive number');
        }
        if ($this->years <= 0) {
            throw new InvalidArgumentException('Invalid date, the year must be a positive number');
        }
        if ($this->days > 31) {
            throw new InvalidArgumentException('Invalid day');
        }
        if ($this->months > 12) {
            throw new InvalidArgumentException('Invalid month');
        }

        if ($this->hours > 23) {
            throw new InvalidArgumentException('Invalid hours value');
        }
        if ($this->minutes > 59) {
            throw new InvalidArgumentException('Invalid minutes value');
        }

        if (!$this->value && $this->value !== 0) {
            throw new InvalidArgumentException('Invalid value');
        }
    }

    public function changeDate($date, $op, $value)
    {
        $this->op = $op;
        $this->value = $value;
        $this->date = $date;

        $this->minutes = substr($this->date, 14, 2);
        $this->hours = substr($this->date, 11, 2);
        $this->years = substr($this->date, 6, 4);
        $this->months = substr($this->date, 3, 2);
        $this->days = substr($this->date, 0, 2);

        $this->validateInputData();

        if ($this->value < 0) {
            $this->value *= -1;
        }

        if ($this->op == '+') {
            $this->addMinutesToDate();
        } else if ($this->op == '-') {
            $this->subtractMinutesFromDate();
        }

        return $this->getFormattedDate();
    }
}
