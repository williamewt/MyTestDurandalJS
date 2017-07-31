define(['plugins/router', 'durandal/app'], function (router, app) {
    return {
        router: router,
        search: function() {
            //It's really easy to show a message box.
            //You can add custom options too. Also, it returns a promise for the user's response.
            app.showMessage('Search not yet implemented...');
        },
        activate: function () {
            router.map([
                { route: '', title:'Welcome', moduleId: 'viewmodels/welcome', nav: false },
                { route: 'criarconta', title:'Criar Conta', moduleId: 'viewmodels/criarconta', nav: false },
                { route: 'entrar', title: 'Entrar', moduleId: 'viewmodels/entrar', nav: false },
                { route: 'perfil', title: 'Perfil', moduleId: 'viewmodels/perfil', nav: true },
                { route: 'flickr', moduleId: 'viewmodels/flickr', nav: true }
            ]).buildNavigationModel();
            
            return router.activate();
        }
    };
});