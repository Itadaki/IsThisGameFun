/* 
 * Autor = Diego Rodríguez Suárez-Bustillo
 * Fecha = 19-may-2015
 * Licencia = gpl30
 * Version = 1.0
 * Descripcion = 
 */

/* 
 * Copyright (C) 2015 Diego Rodríguez Suárez-Bustillo
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
//When expand panel
$(document).ready(function() {
    extendSaga();
});
function extendSaga() {
    $('.saga-name').click(function() {
        var open = $(this).find('.caret').parent().hasClass('dropup');
        if (!open) {
            //Si la saga no esta desplegada se despliega y cambia la flecha hacia arriba.
            $(this).siblings('.saga').animate({height: '100%'}, 500);
            $(this).find('.caret').parent().addClass('dropup');
            open = $(this).find('.caret').parent().hasClass('dropup');
        } else {
            //Si la saga esta desplegada se cierra y cambia la flecha hacia abajo.
            $(this).siblings('.saga').animate({height: '0px'}, 500);
            $(this).find('.caret').parent().removeClass('dropup');
            open = $(this).find('.caret').parent().hasClass('dropup');
        }
    });
}
