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
$('#form-login').bootstrapValidator({
//        live: 'disabled',
        message: '',
        fields: {
            user: {
                message: '',
                validators: {
                    notEmpty: {
                        message: ''
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: ''
                    }
                }
            }
        }
    });

    // Validate the form manually
    $('#validateBtn').click(function() {
        $('#defaultForm').bootstrapValidator('validate');
    });

    $('#resetBtn').click(function() {
        $('.msg').remove();
        $('#defaultForm').data('bootstrapValidator').resetForm(true);
    });
    $('small').css({font:'15px',color:'white'});
    $('label').css({font:'15px',color:'white'});
    $('i').css({font:'15px',color:'#777777'});
});

