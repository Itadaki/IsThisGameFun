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
$(document).ready(voteProfileGame());
function voteProfileGame() {
    $('.profile-btn').click(function () {
        var button = $(this);
        var msg_vote;
        var parent = button.parent().parent();
        var game = parent.attr('class').split(" ")[1];
        var game_id = game.split('-')[1];
        var vote_value = button.hasClass('pull-left');
        (vote_value)?msg_vote = 'up':msg_vote = 'down';
        var user_vote = $('.user-vote').html();
        var data_send = JSON.stringify({game_id: game_id, vote: vote_value});
        loadPanelProfile(parent);
        loadIconProfile(parent);
        $.post(server_root + 'api/vote', {json: data_send}, function (data) {
            console.log(user_vote + '-' + vote_value);
            var error = data.error;
            var msg = data.message;
            var state;
            if (error) {
                state = "danger";
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
                        loadProgressBarProfile(vote_value, user_vote);
                    }
                }
            }
            loadMessageProfile(parent, state, msg);
            $('.load-vote').delay(1000).fadeOut();
            $('.alert').delay(1000).fadeOut();
        });
    });
}
function loadPanelProfile(parent) {
    $(parent).append('<div class="load-vote"></div>');
    $('.load-vote').css({position: 'absolute', top: '0px', height: '100%', width: '90%', opacity: '0.5', background: 'black'});
}
function loadIconProfile(parent) {
    $(parent).append('<img src="' + server_root + 'assets/images/loading.gif" id=loading-icon>');
    $('#loading-icon').css({position: 'absolute', top: '50%', opacity: '1', left: '40%', height: '50px'});
}
function loadMessageProfile(parent, state, msg) {
    $('#loading-icon').remove();
    $(parent).append('<div class="alert alert-' + state + '" >' + msg + '</div>');
    $('.alert').css({position: 'absolute', top: '50%', opacity: '1', width: '90%'});
}
function loadProgressBarProfile(vote_value, user_vote) {
    var positives = $('.positive-votes').html();
    var total = $('.total-votes').html();
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
    $('.positive-votes').html(positives);
    $('.total-votes').html(total);
    $('.progress-bar-info').css({width: positive_percentage + '%'});
    $('.progress-bar-info').text(positive_percentage + '%');
    if (user_vote === '' || user_vote === 'null') {
        $(".user-vote").html((vote_value) ? '1' : '0');
    } else {
        (user_vote === '0') ? $(".user-vote").html('1') : $(".user-vote").html('0');
    }
    console.log(positives);
    console.log(total);
    console.log(positive_percentage);
}
