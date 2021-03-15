<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss("/local/components/sch/schedule/templates/.default/css/styles.css");
Asset::getInstance()->addJs("/local/components/sch/schedule/templates/.default/js/scripts.js");
global $APPLICATION;
CModule::IncludeModule('iblock');
?>
<section class="schedule">
    <h1 class="container schedule-title">Расписание трансляций</h1>
    <ul class="schedule-months-list">
        <? /* Получаем список месяцев */ ?>
        <? $i = 1;
        foreach ($arResult["MONTH_LIST"] as $monthItem) { ?>
            <? /* Получаем название месяца */ ?>
            <li class="schedule-months-list__item<? if ($i == $arResult["MONTH"]) {
                echo ' active';
            } ?>" data-id="month-<?= $i; ?>">
                <span><?= $monthItem ?></span>
            </li>
            <? $i++;
        } ?>
    </ul>
    <? /* Перебираем дни в месяце */ ?>
    <? for ($i = 1; $i <= 12; $i++) { ?>
        <? /* Выводим таб с числами */ ?>
        <ul class="schedule-days-list<? if ($i == $arResult["MONTH"]) {
            echo ' active';
        } ?>" id="month-<?= $i; ?>">
            <? /* Получаем количество дней в месяце */ ?>
            <? for ($j = 1; $j <= (int)cal_days_in_month(CAL_GREGORIAN, $i, $arResult["YEAR"]); $j++) { ?>
                <? /* Формируем дату, добавляем нули */ ?>
                <?
                if (strlen($j) == 1) {
                    $day = '0' . $j;
                } else {
                    $day = $j;
                }
                if (strlen($i) == 1) {
                    $month = '0' . $i;
                } else {
                    $month = $i;
                }
                ?>
                <? /* Выводим числа */ ?>
                <li class="schedule-days-list__item<? if ($i == $arResult["MONTH"] && ($j == $arResult["DAY"])) {
                    echo ' active';
                } ?>" date-attr='<?= $day . '.' . $month . '.' . $arResult["YEAR"]; ?>'>
                    <span><?= $j ?></span>
                </li>
            <? } ?>
        </ul>
    <? } ?>
    <div class="container schedule-list">
        <? /* Получаем список трансляций за текущее число */ ?>
        <?
        /* Параметры фильтра(Битрикс API) */
        $arSelectSchedule = Array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "DETAIL_PAGE_URL",
            "DATE_ACTIVE_FROM",
            "PREVIEW_PICTURE",
            "PROPERTY_*"
        );
        $arFilterSchedule = Array(
            "IBLOCK_ID" => 90,
            "ACTIVE" => "Y",
            "DATE_ACTIVE_FROM" => $arResult["CURRENT_DATE"]
        );
        $resSchedule = CIBlockElement::GetList(Array(), $arFilterSchedule, false, Array(), $arSelectSchedule);
        while ($obSchedule = $resSchedule->GetNextElement()):
            /* Получаем список полей и свойств трансляции */
            $arFieldsSchedule = $obSchedule->GetFields();
            $arPropsSchedule = $obSchedule->GetProperties();
            ?>
            <div class="schedule-list__item" id="<?= $arFieldsSchedule['ID']; ?>">
                <div class="schedule-list__item-img">
                    <? if (!empty($arFieldsSchedule["PREVIEW_PICTURE"])) { ?>
                        <img src="<?= CFile::GetPath($arFieldsSchedule["PREVIEW_PICTURE"]); ?>"
                             alt="<?= $arFieldsSchedule["NAME"] ?>">
                    <? } else { ?>
                        <img src="/local/components/sch/schedule/templates/.default/img/free-webinar.jpg"
                             alt="<?= $arFieldsSchedule["NAME"] ?>">
                    <? } ?>
                </div>
                <div class="schedule-list__item-head">
                    <div class="schedule-list__item-author">
                        <?= $arPropsSchedule["AUTHOR"]["VALUE"] ?>
                    </div>
                    <div class="schedule-list__item-title">
                        <a href="<?= $arFieldsSchedule["DETAIL_PAGE_URL"] ?>"><?= $arFieldsSchedule["NAME"] ?></a>
                    </div>
                </div>
                <div class="schedule-list__item-date">
                    <?= $arPropsSchedule["TRANSLATION_DATE"]["VALUE"] ?>
                </div>
                <div class="schedule-list__item-subscribe">
                    <? $APPLICATION->IncludeComponent(
                        "mybiz:chat.subscribe_btn",
                        "",
                        array(
                            "EXTERNAL_CHAT_ID" => $arPropsSchedule["EXTERNAL_ID"]["VALUE"],
                            "RELOAD_PAGE_AFTER_SUBSCRIBE" => 0,
                        ),
                        false
                    ); ?>
                </div>
            </div>
        <? endwhile;
        if ($resSchedule->SelectedRowsCount() == 0): ?>
            <div class="schedule-list__item empty">На выбранный день трансляции отсутствуют!</div>
        <? endif; ?>
    </div>
</section>
