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
        var disabled = button.siblings('button').attr('disabled');
        var parent = button.parents('.game');
        var user_vote = parent.children(".user-vote").html();
        var id_name = parent.attr('id');
        var id = id_name.split('-')[1];
        var vote_value = button.hasClass('pull-left');
        var data_send = JSON.stringify({game_id: id, vote: vote_value});
        console.log(user_vote);
        loadPanel(id_name);
        loadIcon(id_name);
        $.post(server_root + 'api/vote', {json: data_send}, function (data) {
//            data = $.parseJSON(data);
console.log(data);
            var error = data.error;
            var state;
            var msg = data.message;
            if (error) {
                state = "danger";
            } else {
                if (user_vote === '1' && vote_value === true) {
                    state = "danger";
                    msg = 'The game has already been voted postive by you'
                } else {
                    if (user_vote === '0' && vote_value === false) {
                        state = "danger";
                        msg = 'The game has already been voted negative for you'
                    } else {
                        state = "success";
                        button.attr('disabled', true);
                        button.siblings('button').attr('disabled', false);
                        loadProgressBar(button, vote_value, disabled, user_vote);
                        if (user_vote === '' || user_vote=== 'null') {
                            button.siblings(".user-vote").html((vote_value)?'1':'0');
                        } else {
                           (user_vote === '0')? button.siblings(".user-vote").html('1'):button.siblings(".user-vote").html('0');
                        }
                    }
                }
            }
            loadMessage(id_name, state, msg);
            $('.load-vote').delay(1000).fadeOut();
            $('.alert').delay(1000).fadeOut();
        });
    });
});
function loadPanel(field) {
    $('#' + field).append('<div class="load-vote"></div>');
    $('.load-vote').css({position: 'absolute', top: '20px', height: '100%', width: '100%', opacity: '0.5', background: 'black'});
}
function loadIcon(field) {
    $('#' + field).append('<img src="' + server_root + '/img/loading.gif" id=loading-icon>');
    $('#loading-icon').css({position: 'absolute', top: '50%', opacity: '1', left: '40%', height: '50px'});
}
function loadMessage(field, state, msg) {
    $('#loading-icon').remove();
    $('#' + field).append('<div class="alert alert-' + state + '" >' + msg + '</div>');
    $('.alert').css({position: 'absolute', top: '50%', opacity: '1', width: '100%'});
}
function loadProgressBar(field, vote_value, disabled, user_vote) {
    var positives = field.siblings('.positive-votes').html();
    console.log((positives));
    var total = field.siblings('.total-votes').html();
    console.log((total));
    parseInt(positives);
    parseInt(total);

    if (!(user_vote === '')) {
        (vote_value) ? positives++ : positives--;
    } else {
        (vote_value) ? positives++ : "";
        total++;
    }
    var positive_percentage = (positives / total) * 100;
    positive_percentage = parseInt(positive_percentage);
    field.siblings('.positive-votes').text(positives);
    field.siblings('.total-votes').text(total);
    field.siblings('#total-votes').children('.total').remove();
    field.siblings('#total-votes').append('<span class="total"> ' + total + ' votes</span>');
    field.siblings('.progress').children('.progress-bar-info').css({width: positive_percentage + '%'});
    field.siblings('.progress').children('.progress-bar-info').children('.positive-percentaje').remove();
    field.siblings('.progress').children('.progress-bar-info').append('<span class="positive-percentaje">' + positive_percentage + '%</span>');
}