$(document).ready(function() {
    $('.json-field').on('blur','.source', function(e){
        var $s = $(e.target),
            $r = $('.result', $(e.target.parentElement)),
            reformat = $(e.delegateTarget).hasClass('reformat'),
            friendly = $(e.delegateTarget).hasClass('friendly');

        try {
            var result = jsonlint.parse($s.val());
            if (result) {
                $s.removeClass('fail').addClass('pass');
                $r.html("JSON is valid!").removeClass('fail').addClass('pass');
              
                if (reformat) {
                    $s.val(JSON.stringify(result, null, "  "));
                };

            } else {
               $r.html('something is wrong').removeClass('pass').addClass('fail'); 
            }
        } catch(e) {
            var message = friendly ? 'Invalid format!' : e.message;
            $s.removeClass('pass').addClass('fail');
            $r.html(message).removeClass('pass').addClass('fail');
        }
    });
});

