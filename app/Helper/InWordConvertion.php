<?php
namespace App\Helper;

class InWordConvertion
{
    protected static $ones = [
        '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
        'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen',
        'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
    ];

    protected static $tens = [
        '', '',
        'Twenty', 'Thirty', 'Forty', 'Fifty',
        'Sixty', 'Seventy', 'Eighty', 'Ninety'
    ];

    public static function convert($amount)
    {
        $amount = round($amount, 2);

        $taka = floor($amount);
        $poisha = round(($amount - $taka) * 100);

        $words = self::convertNumber($taka);

        $result = "Taka {$words}";

        if ($poisha > 0) {
            $result .= " and " . self::convertNumber($poisha) . " Poisha";
        }

        return trim($result) . " Only";
    }

    protected static function convertNumber($number)
    {
        if ($number == 0) {
            return "Zero";
        }

        $parts = [];

        $crore = floor($number / 10000000);
        if ($crore > 0) {
            $parts[] = self::convertBelowThousand($crore) . " Crore";
            $number %= 10000000;
        }

        $lakh = floor($number / 100000);
        if ($lakh > 0) {
            $parts[] = self::convertBelowThousand($lakh) . " Lakh";
            $number %= 100000;
        }

        $thousand = floor($number / 1000);
        if ($thousand > 0) {
            $parts[] = self::convertBelowThousand($thousand) . " Thousand";
            $number %= 1000;
        }

        if ($number > 0) {
            $parts[] = self::convertBelowThousand($number);
        }

        return implode(' ', $parts);
    }

    protected static function convertBelowThousand($number)
    {
        $result = '';

        if ($number >= 100) {
            $result .= self::$ones[(int)floor($number / 100)] . ' Hundred';
            $number %= 100;

            if ($number > 0) {
                $result .= ' and ';
            }
        }

        if ($number >= 20) {
            $result .= self::$tens[(int)floor($number / 10)];

            if ($number % 10) {
                $result .= ' ' . self::$ones[$number % 10];
            }
        } elseif ($number > 0) {
            $result .= self::$ones[$number];
        }

        return trim($result);
    }
}