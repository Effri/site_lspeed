/* SHOP */

$('#generate_wallet').on('click', function (event) {

    event.preventDefault();

    /* MAYBE AFTER DONALD TRUMP
    let get_server_name = $('.jq-selectbox__select:eq(0)').text();
    let server_id = 1;

    if(get_server_name == "Portland") {
        server_id = 1;
    }
    */

    let params = {};
    params.server = $('#server').val();;
    params.money = $('#sum').val();
    params.account = $('#account').val();

    console.log(params);
    if ($('#sum').val() && $('#account').val()) {

        if (params.money >= 100) {
            $.ajax({
                type: 'GET',
                url: '/request/donate',
                dataType: 'JSON',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    account: params.account,
                    server: params.server,
                    sum: params.money,
                },
                beforeSend: function () {
                    $('#generate_wrong').html('<label style="color: blue;">Попытка создания платежа<label>');
                },
                success: function (data) {
                    console.log(data);
                    if (data.error)
                        $('#generate_wrong').html(`<label style="color: red;">${data.error}</label>`);

                    if (data.url) window.location.href = data.url;
                    // window.location.href = 'https://unitpay.ru/pay/262601-ca4aa/yandex?account=' + data.callback.account + '&sum=' + data.callback.sum + '&desc=' + data.callback.desc + '&signature=' + data.callback.hash;
                }
            });
        } else {
            $('#generate_wrong').html(`<label style="color: red;">Сумма доната должна быть более 100 RUB<label>`);
        }

    } else {
        $('#generate_wrong').html(`<label style="color: red;">Необходимо заполнить все поля<label>`);
    }

});
var selected_flc = 0;
$('#selected_flc').html(`${selected_flc} FLC`);
$(".select_product").click(function () {
    $(this).find('.block_box').toggleClass('active');
    if ($(this).find('.block_box').hasClass('active')) {
        $(this).find('input:checkbox').attr('checked', true);
        selected_flc += $(this).data('price');
    }
    else {
        $(this).find('input:checkbox').attr('checked', false);
        selected_flc -= $(this).data('price');
    }
    $('#selected_flc').html(`${selected_flc} FLC`);
    if (Math.ceil(selected_flc) < 100) $('#sum').val(100);
    else $('#sum').val(Math.ceil(selected_flc));
});
// $('.go_to_donate_form').click(function () {
//     if (Math.ceil(selected_flc) < 100) $('#sum').val(100);
//     else $('#sum').val(Math.ceil(selected_flc));

//     $('#account').focus();
// });
$('.server_items .nav .button').click(function () {
    $('.server_items .nav .button').each(function () { $(this).removeClass('active') });
    $('.server_items .content .products').each(function () { $(this).hide() });
    $(this).addClass('active');
    $(`.server_items .content .products.${$(this).data('open')}`).show();
})