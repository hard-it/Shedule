<? /* Подключаем пролог и модуль для работы с данными ИБ*/
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule('iblock');
global $APPLICATION;
/* Вытаскваем выбранную дату */
$date = $_POST['date'];
/* Получаем список трансляций за выбранное число */
/* Параметры фильтра(Битрикс API) */
$arSelectSchedule = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "PROPERTY_*");
$arFilterSchedule = Array("IBLOCK_ID" => 90, "ACTIVE" => "Y", "DATE_ACTIVE_FROM" => $date);
$resSchedule = CIBlockElement::GetList(Array(), $arFilterSchedule, false, Array(), $arSelectSchedule);
while($obSchedule = $resSchedule->GetNextElement()):
    /* Получаем список полей и свойств трансляции */
    $arFieldsSchedule = $obSchedule->GetFields();
    $arPropsSchedule = $obSchedule->GetProperties();
    ?>
    <div class="schedule-list__item" id="<?=$arFieldsSchedule['ID']; ?>">
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
<? endwhile; if ($resSchedule->SelectedRowsCount()==0):?>
    <div class="schedule-list__item empty">На выбранный день трансляции отсутствуют!</div>
<? endif; ?>