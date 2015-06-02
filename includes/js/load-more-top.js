/* 
 * Copyright (C) 2015 Javier Oltra Riera
 *
 * This program is free software: you can redispan_platformsibute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is dispan_platformsibuted in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$(document).ready(function () {
    var offset = 1;
    var type;
    $('.load-more').click(function () {
        label++;
        var url = $(location).attr('href');
        url = url.split('/');
        if (url[5] === 'top') {
            type = 1;
        }
        if (url[5] === 'newest') {
            type = 2;
        }
        if (url[5] === 'all') {
            type = 3;
        }
        offset = offset + 20;
        console.log(offset);
        var ruta = server_root + 'api/getMoreGames/' + type + '/' + offset;
        $.get(ruta, function (data) {
            console.log(data);
            var parent = $('#game-container');
            addGames(parent, data);
            vote();
            if(data.full_quota === false){
                $('.load-more').addClass('disabled');
            }
        });
    });
});
function addGames(parent, data) {
    $.each(data.games, function (i, item) {
        var disabled_left='';
        var disabled_right='';
        if (item.my_vote === 1){
            disabled_left = 'disabled';
        }
        if (item.my_vote === 0){
            disabled_right = 'disabled';
        }
        parent.append('<div class="game-small col-md-3 col-sm-4 col-xs-6 text-center">\n\
            ' + addCard(item) + addImg(item) + addPlatforms(item) + addVote(item, disabled_right, disabled_left));
    });

}
function addCard(item) {
    var div_card = '<div class="card">\n\
                <div class="game" id="game-' + item.id + '">\n\
                <a href="' + server_root + 'games/details/' + item.id + '"><div class="bg-gray1" style="height: 50px; position: relative">\n\
                <h4 style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; margin: 0"><span>' + item.name + '</span></h4>\n\
                </div></a>';
    return div_card;
}
function addSaga(item) {
    var div_saga;
    var saga = new Array;
    if (item.saga !== null) {
        $.each(item.saga, function (i, value) {
            saga[i]=value;
            
        });
        div_saga = '<span class="bg-gray2" style="min-height: 25px; display: block;"><span>'+saga.name+'</span></span>\n\
                        <button class="btn btn-block btn-danger close-saga"><span class="glyphicon glyphicon-eye-close"></span></button>\n\
                        <img class="img-responsive" style="margin:0px auto 15px auto;max-height: 50px" src="'+server_root+'logos/'+saga.logo+'" alt="Game-Cover" />\n\
                        <div style=" overflow:auto; height: 195px">\n\
                        '+saga.description+'\n\
                        </div>';
    }
    return div_saga;

}
function addImg(item) {
    var saga ='';
    if (item.saga !== null){
        saga = 'Saga<span class="caret"></span>';
    }
    var div_img = '<div style="position: relative; height: 368px; overflow:hidden">\n\
                    <div class="bg-gray2 saga-name" style="cursor: pointer" >'+saga+'</div>\n\
                    <a href="' + server_root + 'games/details/' + item.id + '">\n\
                        <img class="img-responsive" style="margin:0px auto 15px auto;position: absolute;" src="' + server_root + 'covers/' + item.cover + '" alt="' + item.name + '" />\n\
                    </a>\n\
                    <div class="saga bg-gray3" style="position: relative; max-height: 100%;">' + addSaga(item) + '</div>\n\
                  </div>';
    return div_img;
}
function addPlatforms(item) {
    var span_platforms = "";
    $.each(item.platforms, function (i, value) {
        if (Object.keys(item.platforms).length > 1) {
            span_platforms += '<span class="label label-platform" title="' + value.name + '">' + value.short_name + '</span>\n';
        } else {
            span_platforms = '<span class="label label-platform" title="' + value.name + '">' + value.short_name + '</span>\n';
        }
    });
    return span_platforms;
}
function addVote(item, disabled_right, disabled_left) {
    var div_votes = "";
    console.log(disabled_left);
    var votes = new Array();
    $.each(item.vote_balance, function (i, value) {
        votes[i] = value;
    });
    div_votes = '<div>\n\
                    <div class="progress progress-bar-danger voffset2">\n\
                        <div class="progress-bar-info" role="progressbar" aria-valuenow="' + votes.positive_percentage + '" aria-valuemin="0" aria-valuemax="100"  style="width:' + votes.positive_percentage + '%">\n\
                            <span class="positive-percentaje">' + votes.positive_percentage + ' %</span>\n\
                        </div>\n\
                    </div>\n\
                    <div class="hidden positive-votes">' + votes.positive_votes + '</div>\n\
                    <div class="hidden total-votes">' + votes.total_votes + '</div>\n\
                        <button type="button" class="btn btn-vote btn-vote' + label + ' btn-info pull-left positive-vote '+disabled_left+'"><span class="glyphicon glyphicon-thumbs-up"></span></button>\n\
                        <span id="total-votes"><span class="total">' + votes.total_votes + '</span> votes</span>\n\
                        <button type="button" class="btn btn-vote btn-vote' + label + ' btn-danger pull-right negative-vote '+disabled_right+'"><span class="glyphicon glyphicon-thumbs-down"></span></button>\n\
                    </div>\n\
                    <div class="hidden user-vote">' + (item.my_vote) + '</div>\n\
                    </div></div></div>';
    return div_votes;
}