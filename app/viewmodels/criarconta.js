
define(['plugins/http', 'durandal/app', 'knockout', 'knockout-validation'], function (http, app, ko){
    ko.validation.rules.pattern.message = 'Invalido.';

    var valdField = function (val, field) {
        var result;
        $.ajax({
            url: 'http://durundal.mytest.dev/api/validateField.php',
            type: 'POST',
            data: 'field=' + field + '&fieldValue=' + val,
            dataType: 'json',
            async: false,
            success: function (data) {   
                result = data.validate;
            }
        });
        console.log(result);
        return result; 
    };

    var valdPassword = function (val) {
        var pattern = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!@#$%&*()-_+=]{6,}$/);  
        if(pattern.test(val)) {
            return true;
        }else{
            return false; 
        }
    };

    var mustEqual = function (val, other) {
        return val == other;
    };

    var name = ko.observable().extend({ 
        required: {
            params: true,
            message: 'Nome completo é requerido!'
        },
    });
    var email = ko.observable().extend({ 
        required: {
            params: true,
            message: 'E-mail é requerido!'
        },
        pattern: {
            message: 'E-mail inválido',
            params: '^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$'
        },
        validation: {
            validator: valdField,
            message: 'E-mail indisponível.',
            params: 'email'
        }
    });

    var user = ko.observable().extend({ 
        required: {
            params: true,
            message: 'Usuário é requerido!'
        },
        validation: {
            validator: valdField,
            message: 'Usuário indisponível.',
            params: 'user'
        } 
    });

    var password = ko.observable().extend({
        required: {
            params: true,
            message: 'A senha é requerida!'
        },
        validation: {
            validator: valdPassword,
            message: 'A senha deve conter 6 caracteres e pelo menos uma letra maiúscula, uma minúscula e um número.'            
        }
    });

    var confirmPassword = ko.observable().extend({
        required: {
            params: true,
            message: 'Confirme a senha!'
        },
        validation: {
            validator: mustEqual,
            message: 'Senhas diferentes!',
            params: password  
        }
    });      


    var vm = {
        displayName : 'Criar conta',
        name: name,
        email: email,
        user: user,
        password: password,
        confirmPassword: confirmPassword,

        submit: function(){
            if (vm.errors().length === 0){

                var dados = 'name=' + name() + '&email=' + email() + '&user=' + user() + '&password=' + password() + '&confirmPassword=' + confirmPassword();
                console.log(dados);               

                $.ajax({
                    url: 'http://durundal.mytest.dev/api/criarconta.php',
                    type: 'POST',
                    data: dados,
                    dataType: 'json',
                    async: false,
                    success: function(data){            
                        if(data.success === 1){
                            alert(data.msg);
                            $(location).attr('href', '../');
                        }else{
                            alert(data.msg);
                        }
                    },
                    beforeSend: function () {
                        $('#btnSubmit').prop('disabled', true);
                    },
                    complete: function () {
                        $('#btnSubmit').prop('disabled', false);
                    }
                    
                });
            }else{
                vm.errors.showAllMessages();
            }
        }
    };     

    vm['errors'] = ko.validation.group(vm);
    return vm;

});

