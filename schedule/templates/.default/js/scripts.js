$(document).ready(function(){
    $('.schedule-months-list__item').on('click', function(){
        let active = $(this).attr('data-id');
        $('.schedule-months-list__item').removeClass('active');
        $('.schedule-days-list').removeClass('active');
        $(this).addClass('active');
        $('#'+active).addClass('active');
        $('.schedule-list .schedule-list__item').remove();
        $('.schedule-list').append('<div class="schedule-list__item empty">Выберите дату!</div>');
    })
    $('.schedule-days-list__item').on('click', function(){
        let date = $(this).attr('date-attr');
        $('.schedule-days-list__item').removeClass('active');
        $(this).addClass('active');
        $.ajax({
            type: 'POST',
            url: '/local/components/sch/schedule/templates/.default/scripts/action.php',
            data: 'date='+date,
            success: function (data) {
                $('.schedule-list .schedule-list__item').remove();
                $('.schedule-list').append(data);
            }
        });
    })
});