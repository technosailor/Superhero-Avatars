jQuery(document).ready(function($) {

    console.debug( Cookies.get( marvel.cookieName ) );
    //if ( 'undefined' === Cookies.get( marvel.cookieName ) ) {
        var postData = {
            "action": "get_a_hero",
            "nonce": marvel.nonce
        };

        $.post(
            marvel.ajaxUrl,
            postData,
            function( response ) {
                var data = JSON.parse( response );
                Cookies.set( marvel.cookieName, data.data.image_url, { expires: 87600 } );
            }
        );
   // }
});