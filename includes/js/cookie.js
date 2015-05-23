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
    var cookie_compliance = Cookies.get("cookie_compliance");
    console.log(cookie_compliance);
    if (!cookie_compliance) {
        $('body').append('<div class="alert alert-info alert-cookie">\n\
                <a href="#" class="close" data-dismiss="alert">&times;</a>\n\
                <strong id="title-cookie">¡Esta web utiliza cookies!</strong>Bla bla bla\n\
                <button type="button" class="btn btn-info btn-coockie" name="accept_cookie" value="LogIn">Accept</button>\n\
                </div>');
        $('.alert-cookie').css({position: 'absolute', top: '0px', width: '100%'});
        $('.btn-coockie').click(function () {
           Cookies.set("cookie_compliance",'true', {expire:700, path:'/'});
        });

    }

});
