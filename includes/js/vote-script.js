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

$(document).ready(function() {
    $('.btn-vote').click(function() {
        var button = $(this);
        var parent = button.parent();
        var id_name = parent.attr('id');
        var id = id_name.split('-')[1];
        var vote_value = button.hasClass('btn-left');
        var data_send = JSON.stringify({game_id: id, vote: vote_value});

        $.post('api/vote', {json: data_send}, function(data, textStatus) {
            data = $.parseJSON(data);
            var error = data.error;
            var msg_type;
            var msg = data.message;
            
            if (error) {
                msg_type = "danger";
            } else {
                msg_type = "info";
                button.attr('disabled', true);
                button.siblings('button').attr('disabled', false);
            }
                $('#' + id_name).append('<div class="load-vote"></div>');
                $('.load-vote').css({position: 'absolute',top: '10px',height: '100%',width: '262.5px',opacity: '0.5', background: 'black'});
                $('#' + id_name).append('<div class="alert alert-'+msg_type+'" >'+msg+'</div>');
                $('.alert').css({position: 'absolute',top: '50%',opacity: '1',width: '262.5px'});
                
                $('.load-vote').delay(1000).fadeOut();
                $('.alert').delay(1000).fadeOut();
        });
    });
});

