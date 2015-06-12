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
    label = 0;
    vote();
});
function vote() {
    $('.btn-vote' + label).click(function () {
        var button = $(this);
        console.log('OK');
        var parent = button.parents('.game');
        var user_vote = parent.children(".user-vote").html();
        var id_name = parent.attr('id');
        var id = id_name.split('-')[1];
        var vote_value = button.hasClass('pull-left');
        var msg_vote;
        (vote_value)?msg_vote = 'up':msg_vote = 'down';
        var data_send = JSON.stringify({game_id: id, vote: vote_value});
        console.log(user_vote);
        loadPanel(id_name);
        loadIcon(id_name);
        $.post(server_root + 'api/vote', {json: data_send}, function (data) {
            var error = data.error;
            var state;
            var msg = data.message;
            if (error) {
                state = "danger";
                $('.btn-vote').attr('disabled', false);
            } else {
                if (user_vote === '1' && vote_value) {
                    state = "danger";
                    msg = 'The game has already been voted postive by you';
                } else {
                    if (user_vote === '0' && !vote_value) {
                        state = "danger";
                        msg = 'The game has already been voted negative for you';

                    } else {
                        state = "success";
                        msg = 'Success in vote &nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-'+msg_vote+'"></span> ';
                        button.addClass('disabled');
                        button.siblings('button').removeClass('disabled');
                        loadProgressBar(button, vote_value, user_vote);
                        if (user_vote === '' || user_vote === 'null') {
                            parent.children(".user-vote").html((vote_value) ? '1' : '0');
                        } else {
                            (user_vote === '0') ? parent.children(".user-vote").html('1') : parent.children(".user-vote").html('0');
                        }
                    }
                }
            }
            loadMessage(id_name, state, msg);
            $('#' + id_name).children('.load-vote').delay(1000).fadeOut();
            $('#' + id_name).children('.alert').delay(1000).fadeOut();
        });
    });
}
function loadPanel(field) {
    $('#' + field).append('<div class="load-vote"></div>');
    $('.load-vote').css({position: 'absolute', top: '0px', height: '104%', width: '100%', opacity: '0.5', background: 'black'});
}
function loadIcon(field) {
    $('#' + field).append('<img src="' + server_root + 'assets/images/loading.gif" class=loading-icon-vote>');
    $('.loading-icon-vote').css({position: 'absolute', top: '50%', opacity: '1', left: '40%', height: '50px'});
}
function loadMessage(field, state, msg) {
    $('#' + field).children('.loading-icon-vote').remove();
    $('#' + field).append('<div class="alert alert-' + state + '" >' + msg + '</div>');
    $('.alert').css({position: 'absolute', top: '50%', opacity: '1', width: '100%'});
}
function loadProgressBar(field, vote_value, user_vote) {
    var positives = field.siblings('.positive-votes').html();
    var total = field.siblings('.total-votes').html();
    parseInt(positives);
    parseInt(total);

    if (user_vote === '' || user_vote === 'null') {
        (vote_value) ? positives++ : "";
        total++;
    } else {
        (vote_value) ? positives++ : positives--;
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