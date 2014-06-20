$(function(){
    var $auth = $('#auth'), // Блок аутентификации
        $authLoading = $('img#authResponseLoading', $auth);

    // Блок аутентификации:
    $('a#authUsername', $auth).click(function(e){
        e.preventDefault();
    });

    $('a#authLogout', $auth).click(function(e){
        e.preventDefault();

        $authLoading.show();
        $.get('/auth/logout', function(){
            window.location = window.location;
        });
    });

    // В будущем здесь можно заблокирвать переход на страницу входа и вместо этого
    // открывать модальное окно с формой входа в систему (логин осущ. через аякс-запрос):
    $('a#authLogIn', $auth).click(function(e){
//        e.preventDefault();
    });
});

