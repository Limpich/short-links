<?php

namespace App\Helpers;

class LinkHelper
{
    /**
     * Длина ссылки
     */
    public const SHORT_LENGTH = 5;

    public const PRIVATE_LENGTH = 32;

    /**
     * Строка символов для создания коротких ссылок
     */
    private const SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Для удобства реализации не будем отдельно хранить ссылки
     * Будем считать, что ссылка - число в count($synbols)-ричной системе счисления
     * @param int $id
     * @return string
     */
    public static function intToShort(int $id): string
    {
        /** Размер алфавита */
        $alSize = strlen(self::SYMBOLS);

        $result = '';
        while ($id > 0) {
            $digit = $id % $alSize;
            $result .= self::SYMBOLS[$digit];
            $id = (int)($id / $alSize);
        }

        return self::padZeroes($result, self::SHORT_LENGTH);
    }

    /**
     * @param string $short
     * @return int|null
     */
    public static function shortToInt(string $short): ?int
    {
        /** Размер алфавита */
        $alSize = strlen(self::SYMBOLS);

        $id = 0;
        for ($i = 0, $iMax = mb_strlen($short); $i < $iMax; $i++) {
            $char = mb_substr($short, $i, 1);
            try {
                $ord = self::getSymbolPos($char);
            } catch (\Exception $ex) {
                return null;
            }
            $id = $id * $alSize + $ord;
        }

        return $id;
    }

    /**
     * @param int $len
     * @return string
     */
    public static function genPrivate($len = self::PRIVATE_LENGTH): string
    {
        /** Берем первые $len символов */
        return substr(
        /** Перемешиваем строку */
            str_shuffle(
            /** Повторяем строку $len раз */
                str_repeat(
                    self::SYMBOLS,
                    self::PRIVATE_LENGTH
                )
            ),
            0,
            self::PRIVATE_LENGTH
        );
    }

    /**
     * @param string $char
     * @return int|null
     * @throws \Exception
     */
    private static function getSymbolPos(string $char): ?int
    {
        $ord = mb_ord($char);

        /** Костыль для быстрой реализации */
        if (mb_ord('0') <= $ord && $ord <= mb_ord('9')) {
            return $ord - mb_ord('0');
        }

        if (mb_ord('a') <= $ord && $ord <= mb_ord('z')) {
            return $ord - mb_ord('a') + self::getSymbolPos('9') + 1;
        }

        if (mb_ord('A') <= $ord && $ord <= mb_ord('Z')) {
            return $ord - mb_ord('A') + self::getSymbolPos('a') + 1;
        }

        throw new \Exception('Unexpected symbol');
    }

    /**
     * @param string $str
     * @param int $len
     * @return string
     */
    private static function padZeroes(string $str, int $len): string
    {
        return str_pad($str, $len, '0', STR_PAD_LEFT);
    }
}
