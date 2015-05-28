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
        removeIcons(field);
        var user_nick = $(this).val();
        var ruta;
        var data_send = JSON.stringify({user_nick: user_nick});
        (user_nick==="")?ruta = server_root + 'api/checkUserNick/':ruta = server_root + 'api/checkUserNick/' + data_send.getJSON;
        console.log(data_send);
        addLoadingIcon(field);
        $.get(ruta, function (data) {
//            data = $.parseJSON(data);
            console.log(data);
            var error = data.error;
            var message = data.message;
            var exists = data.exists;
            var state;
            (error) ? state = "remove" : ((exists) ? state = "remove" : state = "ok");
            addNickResultIcon(field, state, message);
        }
        );
    });
});
function addNickResultIcon(field, state, msg) {
    $('.loading-icon').remove();
    field.siblings('i').attr("class",'form-control-feedback glyphicon glyphicon-'+state);
    field.siblings('i').attr("style","display:block");
    field.siblings('i').css({color:"#777777"});
    field.parent().append('<small class="help-block msg">'+msg+'</small>'); 
    field.siblings('small').css({color:'white'});
}
function addLoadingIcon(field) {
    field.after('<img src="' + server_root + '/img/loading.gif" class="loading-icon">');
     $('.loading-icon').css({position: 'absolute', top: '10px', left:'80%'});
}
function removeIcons(field) {
    field.siblings('i').attr("style","display:none");
    $('.loading-icon').remove();
    $('.msg').remove();
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
