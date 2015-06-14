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
    $('#nick').blur(function () {
        var field = $(this);
        var isValid = validateNick(field);
        if (isValid) {
            nickIsValid = false;
            var user_nick = $(this).val();
            var ruta;
            var data_send = JSON.stringify({user_nick: user_nick});
            (user_nick === "") ? ruta = server_root + 'api/checkUserNick/' : ruta = server_root + 'api/checkUserNick/' + user_nick;
            console.log(data_send);
            addLoadingIcon(field);
            $.get(ruta, function (data) {
              //Se realiza la peticion al servidor.
                var error = data.error;
                var message = data.message;
                var exists = data.exists;
                nickIsValid = !exists;
                var state;
                (error) ? state = "remove" : ((exists) ? state = "remove" : state = "ok");
                addNickResultIcon(field, state, message);
                enableSubmit();
            }
            );
            field.siblings('.help-block').removeClass('error');
        } else {
            field.siblings('.glyphicon').removeClass('glyphicon-ok');
            field.siblings('.glyphicon').addClass('glyphicon-remove');
            field.siblings('.help-block').text("Alphabetical, numerical, - and _ characters only.")
            field.siblings('.help-block').addClass('error');
            nickIsValid = false;
        }
        enableSubmit();
    });
});
function addNickResultIcon(field, state, msg) {
    $('.loading-icon').remove();
    field.siblings('.glyphicon').removeClass('glyphicon-ok');
    field.siblings('.glyphicon').removeClass('glyphicon-remove');
    field.siblings('.glyphicon').addClass('glyphicon-'+state);
    field.siblings('.help-block').text(msg);
    if (state === "remove"){
        field.siblings('.help-block').addClass('error');
    }
}
function addLoadingIcon(field) {
    field.siblings('.glyphicon').removeClass('glyphicon-ok');
    field.siblings('.glyphicon').removeClass('glyphicon-remove');
    field.after('<img src="' + server_root + 'assets/images/loading.gif" class="loading-icon">');
    $('.loading-icon').css({position: 'absolute', top: '10px', left: '98%'});
}

//    var checkingState = false;
//    var lastNickChecked = '';
//    $('#nick').blur(function () {
//        if (!checkingState && $(this).val().length > 3 && $(this).val() !== lastNickChecked) {
//            //DISABLE CHECKS
//            checkingState = true;
//            $('.nick-result').remove();
//            var field = $(this);
//            var nick = $(this).val();
//            addLoadingIcon(field);
//            $.get(server_root+'api/checkusernick/' + nick, function (data) {
//                $('.loading').remove();
//                data = $.parseJSON(data);
//                var error = data.error;
//                if (error) {
//                    //ERROR
//                } else {
//                    lastNickChecked = nick;
//                    if (data.exists) {
//                        //NICK NOT AVAILABLE
//                        addNickResultIcon(field, false);
//                    } else {
//                        //NICK AVAILABLE
//                        addNickResultIcon(field, true);
//                    }
//                }
//                //ALLOW NEW CHECK
//                checkingState = false;
////                console.log(data);
//            })
//        }
//
//    })
//});
//
//function addLoadingIcon(field){
//    field.after('&nbsp<span class="loading glyphicon glyphicon-refresh gly-spin" aria-hidden="true"></span>');
//}
//function addNickResultIcon(field, result){
//    var state;
//    if (result){
//        state = 'ok';
//    } else {
//        state = 'remove';
//    }
//    field.after('&nbsp<span class="nick-result glyphicon glyphicon-'+state+'" aria-hidden="true"></span>');
//}
//
