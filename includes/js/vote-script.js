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
    $('.btn-vote').click(function () {
        var button = $(this);
        var parent = button.parent();
        var id_name = parent.attr('id');
        var id = id_name.split('-')[1];
        var vote_value = button.hasClass('btn-left');
        var data_send = JSON.stringify({game_id: id, vote: vote_value});

        $.post('api/vote', {json: data_send}, function (data, textStatus) {
            data = $.parseJSON(data);
            var error = data.error;
            var msg_type;
            var msg = data.message;
            if (error) {
                msg_type = "danger";
            } else {
                msg_type = "success";
                button.attr('disabled',true);
                button.siblings('button').attr('disabled',false);
            }
            $('.msg').append('<div class="alert alert-' + msg_type + '">' + msg + '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');
        });
    });
})

