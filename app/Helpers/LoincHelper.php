<?php

if (!function_exists('isValidLoinc')) {

    function isValidLoinc(string $loinc): bool
    {
        // Format harus: angka-angka + '-' + 1 digit
        if (!preg_match('/^\d+-\d$/', $loinc)) {
            return false;
        }

        [$number, $checkDigit] = explode('-', $loinc);

        $digits = str_split(strrev($number));
        $sum = 0;

        foreach ($digits as $i => $digit) {
            $digit = (int) $digit;

            // posisi genap (dari kanan) dikali 2
            if ($i % 2 === 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9; // sama seperti penjumlahan digit
                }
            }

            $sum += $digit;
        }

        $calculated = (10 - ($sum % 10)) % 10;

        return (int)$checkDigit === $calculated;
    }
}


if (!function_exists('generateLoinc')) {
    function generateLoinc(string $number): ?string
    {
        if (!preg_match('/^\d+$/', $number)) {
            return null;
        }

        $digits = str_split(strrev($number));
        $sum = 0;

        foreach ($digits as $i => $digit) {
            $digit = (int) $digit;

            if ($i % 2 === 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $number . '-' . $checkDigit;
    }
}

if (!function_exists('normalizeLoinc')) {
    function normalizeLoinc(string $input): ?string
    {
       // kalau sudah ada check digit → validasi saja
    if (strpos($input, '-') !== false) {
        return isValidLoinc($input) ? $input : null;
    }

    // kalau belum → generate
    return generateLoinc($input);
    }
}
#
