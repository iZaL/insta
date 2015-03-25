/**
 * Created by ZaL on 3/25/15.
 */
function likeMedia(username, media) {
    $.ajax({
        type: "get",
        url: "/instagrams/like-media/" + username + "/" + media,
        data: null,
        success: function (msg) {
            //
        },
        error: function () {
            //
        }
    });

}
function dislikeMedia(username, media) {
    $.ajax({
        type: "get",
        url: "/instagrams/dislike-media/" + username + "/" + media, //
        data: null,
        success: function (msg) {
            //
        },
        error: function () {
            //
        }
    });
}

$('#do-logout').click(function(e){
    e.preventDefault();
    var html = '<img src="http://instagram.com/accounts/logout/" width="0" height="0" />';
    $('#logout').html(html);
    $('#logout').fadeOut();
    $(this).html('Logged Out');
});

$('.make-like').click(function (e) {
    e.preventDefault();
    var username = $(this).data('username');
    var media = $(this).data('media');
    $(this).find('i:first').addClass('green')
    likeMedia(username, media);
});

$('[data-toggle="tooltip"]').tooltip();


