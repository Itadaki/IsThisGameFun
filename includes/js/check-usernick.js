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
    $('#user').focusout(function () {
        $('.glyphicon-ok').remove();
        $('.glyphicon-refresh').remove();
        $('.glyphicon-remove').remove();
        var user_nick = $(this).val();
        var data_send = JSON.stringify({user_nick: user_nick});
        $(this).after('&nbsp<span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>');
        $({deg: 0}).animate({deg: 360}, {
            duration: 2000,
            step: function (now) {
                $('.glyphicon-refresh').css({
                    transform: 'rotate(' + now + 'deg)'
                });
            }
        });
        console.log({json: data_send});
        $.get('api/checkUserNick/' + data_send.getJSON, function (data, textStatus) {
            data = $.parseJSON(data);
            console.log(data);
            var error = data.error;
            var message = data.message;
            var exists = data.exists;

            if (error) {
                alert("error servidor");
            } else {
                if (exists) {
                    $('.glyphicon-refresh').attr('class', 'glyphicon glyphicon-remove');
                    $('.glyphicon-remove').css({
                        transform: 'rotate(0deg)'
                    });
                } else {
                    $('.glyphicon-refresh').attr('class', 'glyphicon glyphicon-ok');
                    $('.glyphicon-ok').css({
                        transform: 'rotate(0deg)'
                    });
                }

            }
        }
        );
    });
});

