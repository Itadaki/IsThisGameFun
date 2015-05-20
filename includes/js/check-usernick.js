/* 
 * Copyright (C) 2015 Javier Oltra Riera
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
$(document).ready(function () {
//    $('#user').focusout(function () {
//        $('.glyphicon-ok').remove();
//        $('.glyphicon-refresh').remove();
//        $('.glyphicon-remove').remove();
//        var user_nick = $(this).val();
//        var data_send = JSON.stringify({user_nick: user_nick});
//        $(this).after('&nbsp<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>');
//        $({deg: 0}).animate({deg: 360}, {
//            duration: 2000,
//            step: function (now) {
//                $('.glyphicon-refresh').css({
//                    transform: 'rotate(' + now + 'deg)'
//                });
//            }
//        });
//        console.log({json: data_send});
//        $.get('api/checkUserNick/' + data_send.getJSON, function (data, textStatus) {
//            data = $.parseJSON(data);
//            console.log(data);
//            var error = data.error;
//            var message = data.message;
//            var exists = data.exists;
//
//            if (error) {
//                alert("error servidor");
//            } else {
//                if (exists) {
//                    $('.glyphicon-refresh').attr('class', 'glyphicon glyphicon-remove');
//                    $('.glyphicon-remove').css({
//                        transform: 'rotate(0deg)'
//                    });
//                } else {
//                    $('.glyphicon-refresh').attr('class', 'glyphicon glyphicon-ok');
//                    $('.glyphicon-ok').css({
//                        transform: 'rotate(0deg)'
//                    });
//                }
//
//            }
//        }
//        );
//    });

    var checkingState = false;
    var lastNickChecked = '';
    $('#nick').blur(function () {
        if (!checkingState && $(this).val().length > 3 && $(this).val() !== lastNickChecked) {
            //DISABLE CHECKS
            checkingState = true;
            $('.nick-result').remove();
            var field = $(this);
            var nick = $(this).val();
            addLoadingIcon(field);
            $.get(server_root+'api/checkusernick/' + nick, function (data) {
                $('.loading').remove();
                data = $.parseJSON(data);
                var error = data.error;
                if (error) {
                    //ERROR
                } else {
                    lastNickChecked = nick;
                    if (data.exists) {
                        //NICK NOT AVAILABLE
                        addNickResultIcon(field, false);
                    } else {
                        //NICK AVAILABLE
                        addNickResultIcon(field, true);
                    }
                }
                //ALLOW NEW CHECK
                checkingState = false;
//                console.log(data);
            })
        }

    })
});

function addLoadingIcon(field){
    field.after('&nbsp<span class="loading glyphicon glyphicon-refresh gly-spin" aria-hidden="true"></span>');
}
function addNickResultIcon(field, result){
    var state;
    if (result){
        state = 'ok';
    } else {
        state = 'remove';
    }
    field.after('&nbsp<span class="nick-result glyphicon glyphicon-'+state+'" aria-hidden="true"></span>');
}

