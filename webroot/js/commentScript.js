/**
 * Created by Kalle Ekelund on 2015-01-25.
 */

$(document).ready(function(){
    $('a.addCommentToQuestion').click(function() {
       $('#newCommentQuestion').show();
    });
    $('a.addCommentToAnswer').click(function() {
        var id = $(this).attr('id');
        id = id.replace('addCommentToAnswer', '');
        console.log(id);
        $('#newCommentToAnswer' + id).show();
    });
});