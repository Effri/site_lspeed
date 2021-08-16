$(document).ready(function () {
    /*
    var defaults = {
        containerID: 'toTop', // fading element id
        containerHoverID: 'toTopHover', // fading element hover id
        scrollSpeed: 1200,
        easingType: 'linear'
    };


    $().UItoTop({ easingType: 'easeOutQuart' });
    */
});
var Interval = 10000;
var timer = setInterval(get_server, Interval);

function get_server() {

    var srv_rds = {
        '1': {
            'ip': '46.174.50.60',
            'name': 'Black'
        },
        '2': {
            'ip': '46.174.50.60',
            'name': 'White'
        }
    };

    var jqxhr = $.getJSON('https://cdn.rage.mp/master/', function (data) {

        jqxhr.success(function () {

            $.each(data, function (ind, val) {

                $.each(srv_rds, function (indx, valx) {

                    if (ind.indexOf(srv_rds[indx]['ip']) + 1) {

                        for (var s in val) {
                            if (val.hasOwnProperty(s)) {

                                var maxplayers = val['maxplayers'];
                                var players = val['players'];
                                var proc = maxplayers / 100;
                                var width = players / proc;

                            }
                        }

                        $('#upd_' + srv_rds[indx]['name'] + ' .now').html(players);
                        $('#upd_' + srv_rds[indx]['name'] + ' .online-all').html(' / ' + maxplayers);
                        // $('#upd_' + srv_rds[indx]['name'] + ' .serv__rd__text span').html(players + ' / ' + maxplayers);
                        // $('#upd_' + srv_rds[indx]['name'] + ' .serv__rd__text').css('width', width + '%');

                        delete srv_rds[ind];

                    }

                });

            });

        })

        jqxhr.error(function () {
            console.log("no connection");
        })

        $.each(srv_rds, function (index, value) {
            $('#upd_' + srv_rds[index]['name'] + ' .now').html('<span style="color:white;">Выключен</span>');
            // $('#upd_' + srv_rds[index]['name'] + ' .serv__rd__text').css('width', '0%');
        });

    });

    if (!jqxhr) {
        $.each(srv_rds, function (index, value) {
            $('#upd_' + srv_rds[index]['name'] + ' .now').html('<span style="color:white;">Выключен</span>');
            // $('#upd_' + srv_rds[index]['name'] + ' .serv__rd__text').css('width', '0%');
        });
    }
}

get_server();

$(".download__multiplayer").click(function () {
    window.location = 'https://cdn.gtanet.work/RAGE_Multiplayer.zip';
});


function CopyToClipboard(containerid) {
    var ct = $('#' + containerid + ' span');
    var a = $('#' + containerid + ' a');
    var prevHtml = a.html();
    var $temp = $("<input style='opacity:0;'>");
    $("body").append($temp);
    $temp.val(ct.text()).select();
    document.execCommand("copy");
    $temp.remove();
    a.text('Скопирован!');
    setTimeout(function () {
        $('#' + containerid + ' a').text(prevHtml)
    }, 700)
}

$(".not_private").on('click', function () {
    alertify.notify('Данный раздел в разработке :)', 'default_rd', 3);
    $('.js-controller').removeClass('active');
});

get_server();