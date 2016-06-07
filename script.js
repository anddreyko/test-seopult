$(document).ready(function(){
	$('form').submit(function(e){
		e.preventDefault();
		e.stopPropagation();
        send();
    });
	$('#input').keyup(send);
    function send(){
        var textButtonSubmit = 'Отправить',
            textButtonWait = 'Ждите..';
        $('#submit').attr('disabled', true)
        .val(textButtonWait);
        var data = {
            'isAjax': true,
            'action': 'send'
        };
        $.ajax({
            url: 'function.php',
            data: data,
            dataType: 'json',
            type: 'POST',
            success: function(d){
                if( d.status ) {
                    $('#result').text(d.top20Word+"\n"+d.top20RuChar);
                    console.log('true');
                    $('#submit').attr('disabled', false)
                    .val(textButtonSubmit);
                } else {
                    console.log('false');
                    $('#submit').attr('disabled', false)
                    .val(textButtonSubmit);
                }
            },
            error: function(e){
                console.log(e.responseText);
                console.log(e);
                $('#submit').attr('disabled', false)
                .val(textButtonSubmit);
            }
        });
    }
});