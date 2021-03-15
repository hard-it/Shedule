<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/* Получаем текущий год */
$arResult["YEAR"] = date("Y");
/* Получаем текущий месяц */
$arResult["MONTH"] = (int)date("m");
/* Получаем текущее число */
$arResult["DAY"] = (int)date("d");
/* Получаем текущую дату целиком */
$arResult["CURRENT_DATE"] = date("d.m.Y");
/* Список месяцев */
$arResult["MONTH_LIST"] = array(
    "1" => "Январь",
    "2" => "Февраль",
    "3" => "Март",
    "4" => "Апрель",
    "5" => "Май",
    "6" => "Июнь",
    "7" => "Июль",
    "8" => "Август",
    "9" => "Сентябрь",
    "10" => "Октябрь",
    "11" => "Ноябрь",
    "12" => "Декабрь"
);
$this->IncludeComponentTemplate();
