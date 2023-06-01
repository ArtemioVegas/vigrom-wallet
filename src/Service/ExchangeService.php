<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Currency;
use App\Exception\CurrencyNotFound;

class ExchangeService
{
    /**
     * все расчеты происходят в копейках и центах
     */
    public function convertToCurrency(int $amount, Currency $from, Currency $to): int
    {
        if ($from === $to) {
            return $amount;
        }

        $fromRate = $this->getCurrentRate($from);
        $toRate = $this->getCurrentRate($to);

        return (int) round($amount * $fromRate / $toRate);
    }

    /**
     * курс валют может переодически обновляться
     * тут я не делаю логику получения данных с внешних апи, но это сделать не сложно
     * договоримся что валюта по умолчанию RUB, следовательно курс для рубля в системе будет равен 1,
     * а для доллара к примеру 80
     * валют может быть сколько угодно, но все преобразования происходят через рубли
     * так что ниже будет условный код для получения различных значений курсов
     */
    private function getCurrentRate(Currency $currency): int
    {
        switch ($currency) {
            case Currency::RUB:
                return 1;
            case Currency::USD:
                return mt_rand(75, 85);
            default:
                throw new CurrencyNotFound($currency->value);
        }
    }
}
