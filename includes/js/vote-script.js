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
        loadPanel(id_name);
        loadIcon(id_name);
        $.post(server_root + 'api/vote', {json: data_send}, function (data) {
            console.log(data);
            data = $.parseJSON(data);
            var error = data.error;
            var state;
            var msg = data.message;
            if (error) {
                state = "danger";
            } else {
                state = "info";
                button.attr('disabled', true);
                button.siblings('button').attr('disabled', false);
            }
            loadMessage(id_name,state,msg);
            $('.load-vote').delay(1000).fadeOut();
            $('.alert').delay(1000).fadeOut();
        });
    });
});
function loadPanel(field) {
    $('#' + field).append('<div class="load-vote"></div>');
    $('.load-vote').css({position: 'absolute', top: '10px', height: '100%', width: '100%', opacity: '0.5', background: 'black'});
}
function loadIcon(field) {
    $('#' + field).append('<img src="' + server_root + '/img/loading.gif" id=loading-icon>');
    $('#loading-icon').css({position: 'absolute', top: '50%', opacity: '1', left: '40%', height: '50px'});
}
function loadMessage(field,state,msg) {
    $('#loading-icon').remove();
    $('#' + field).append('<div class="alert alert-' + state + '" >' + msg + '</div>');
    $('.alert').css({position: 'absolute', top: '50%', opacity: '1', width: '100%'});
}