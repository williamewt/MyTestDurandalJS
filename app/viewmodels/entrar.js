define(['plugins/http', 'durandal/app', 'knockout', 'knockout-validation'], function (http, app, ko) {
    
    var user = ko.observable().extend({
        required: {
            params: true,
            message: 'E-mail ou Usuário é requerido!'
        },
    });

    var password = ko.observable().extend({
        required: {
            params: true,
            message: 'A senha é requerida!'
        },
    });


    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }

        return true;
    }
   
    
    
    var vm = {
        displayName: 'Criar conta',
        user: user,
        password: password,

        submit: function(){
            if (vm.errors().length === 0) {
                $('#alert-div').removeClass('alert-warning');
                $('#alert-div').removeClass('alert-danger');
                $('#alert-div').addClass('hidden');

                var dados = 'user=' + user() + '&password=' + password();

                $.ajax({
                    url: 'http://durundal.mytest.dev/api/entrar.php',
                    type: 'POST',
                    data: dados,
                    async: false,
                    success: function (data) {
                        console.log(data);                        
                        if (isJson(data)) {
                            data = JSON.parse(data);
                            $('#alert-div').addClass(data.alert);
                            $('#alert-msg').html(data.msg);
                            $('#alert-div').removeClass('hidden');
                           // alert(data.msg);                                                 
                            
                        } else{
                            $.ajax({
                                url: 'http://durundal.mytest.dev/api/checkJwt.php',
                                type: 'POST',
                                data: 'token=' + data,
                                async: false,
                                success: function (response) {
                                    console.log(data);
                                    if (isJson(data)) {
                                        data = JSON.parse(data);
                                        $('#alert-div').addClass(data.alert);
                                        $('#alert-msg').html(data.msg);
                                        $('#alert-div').removeClass('hidden');
                                        // alert(data.msg);                                                 

                                    } else {
                                        alert(data);
                                        //$(location).attr('href', '../perfil');                                 
                                    }
                                },
                                error: function (exception) {
                                    alert('Exeption: ' + exception);
                                }
                            });                                
                        }
                    },
                    error: function (exception) {
                        alert('Exeption: ' + exception);
                    }
                });

            }else{
                vm.errors.showAllMessages();
            }
        }
    }

    vm['errors'] = ko.validation.group(vm);

    return vm;
});