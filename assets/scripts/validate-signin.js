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
var userIsValid = false;
var emailIsValid = false;
var passIsValid = false;
var pass2IsValid = false;
var nickIsValid = false;

$(document).ready(function () {

    $('#user').blur(function () {
        var user = $(this);
        var isValid = validateUserName(user);
        //poner colorines, iconos y mensajes
        includeIcon(user, isValid);
        enableSubmit();
    });
    $('#email').blur(function () {
        var email = $(this);
        var isValid = validateEmail(email);
        //poner colorines, iconos y mensajes
        includeIcon(email, isValid)
        emailIsValid = isValid;
        enableSubmit();
    });

    $('#password').blur(function () {
        var password = $(this);
        var isValid = validatePass(password) && isTheSamePass(password);
        //poner colorines, iconos y mensajes
        includeIcon(password, isValid)
        passIsValid = isValid;
        enableSubmit();
    });

    $('#confirmPassword').blur(function () {
        var password = $(this);
        var isValid = validatePass($('#password')) && password.val() === $('#password').val();
        //poner colorines, iconos y mensajes
        if (isValid) {
            //poner tick
            console.log('valid');
            $('#password').siblings('.glyphicon').removeClass('glyphicon-remove');
            $('#password').siblings('.glyphicon').addClass('glyphicon-ok');
            $('#password').siblings('.help-block').text("The password for the log in.")
            $('#password').siblings('.help-block').removeClass('error');
            password.siblings('.glyphicon').removeClass('glyphicon-remove');
            password.siblings('.glyphicon').addClass('glyphicon-ok');
            password.siblings('.help-block').text("Repeat the password.")
            password.siblings('.help-block').removeClass('error');
        } else {
            console.log('no valid');
            //poner x
            password.siblings('.glyphicon').removeClass('glyphicon-ok');
            password.siblings('.glyphicon').addClass('glyphicon-remove');
            password.siblings('.help-block').text("The password doesn't match!.");
            password.siblings('.help-block').addClass('error');
            //sacar msg
        }
        pass2IsValid = isValid;
        console.log(pass2IsValid);
        enableSubmit();
    });


//    $('.input').blur(enableSubmit());
    //VALIDAR EL NICK ESTA EN CHECK-NICK.JS
    $('#form-signin').submit(function () {

        return userIsValid && emailIsValid && passIsValid && pass2IsValid && nickIsValid;
    });
    $('#resetBtn').click(function (){
        $('.glyphicon').removeClass('glyphicon-remove');
        $('.glyphicon').removeClass('glyphicon-ok');
        $('.help-block').removeClass('error');
        $('#user').siblings('.help-block').text('The name you will log in with.');
        $('#nick').siblings('.help-block').text('The public name that will be shown to everyone.');
        $('#email').siblings('.help-block').text('Your email for information purposes.');
        $('#password').siblings('.help-block').text('The password for the log in.');
        $('#confirmPassword').siblings('.help-block').text('Repeat the password.');
    });
        
    
});

function enableSubmit() {
    if (userIsValid && emailIsValid && passIsValid && pass2IsValid && nickIsValid) {
        $('#btn-submit').removeClass('disabled');
    } else {
        $('#btn-submit').addClass('disabled');
    }
}
//Funciones de evaluacion
function validateUserName(user) {
    var regexp = /^[a-zA-ZÑñ0-9_-]{3,45}$/;
    return regexp.test(user.val());
}

function validateEmail(email) {
    var regexp = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9-]{2,}[.][a-zA-Z]{2,4}$/;
    return regexp.test(email.val());
}

function validatePass(pass) {
    var regexp = /^[a-zA-ZÑñ0-9_-]{3,45}$/;
    return regexp.test(pass.val());
}
function validateNick(nick) {
    var regexp = /^[a-zA-ZÑñ0-9_-]{3,45}$/;
    return regexp.test(nick.val());
}
function isTheSamePass(pass){
    var confirm = true;
    if ($('#confirmPassword').val()!== ''){
        if (pass.val() === $('#confirmPassword').val()){
            confirm = true;
        } else {
            confirm = false;
        }
    }
    return confirm;
}
function includeIcon(element, isValid){
    if (isValid) {
            //poner tick
            console.log('valid');
            element.siblings('.glyphicon').removeClass('glyphicon-remove');
            element.siblings('.glyphicon').addClass('glyphicon-ok');
            element.siblings('.help-block').removeClass('error');
        } else {
            console.log('no valid');
            //poner x
            element.siblings('.glyphicon').removeClass('glyphicon-ok');
            element.siblings('.glyphicon').addClass('glyphicon-remove');
            element.siblings('.help-block').text("Alphabetical, numerical, - and _ characters only.")
            element.siblings('.help-block').addClass('error');
            //sacar msg
        }
}