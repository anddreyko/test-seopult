$(document).ready(function(){
	$('form').submit(function(e){
		e.preventDefault();
		e.stopPropagation();
        send();
    });
	$('#input').keyup(send);
    function send(){
        var textButtonSubmit = 'Start',
            textButtonWait = 'Wait..';
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
                    $('#result').text(
                        d.top20Word+"\n"
                      + 'Time Execution: '+d.timeExec20Word+"sec\n\n"
                      + d.top20RuChar+"\n"
                      + 'Time Execution: '+d.timeExec20RuChar+'sec'
                    );
                    console.log('true');
                    $('#submit').attr('disabled', false)
                    .val(textButtonSubmit);
                } else {
                    console.log('false');
                    $('#result').text('Error on server');
                    $('#submit').attr('disabled', false)
                    .val(textButtonSubmit);
                }
            },
            error: function(e){
                console.log(e.responseText);
                console.log(e);
                $('#result').text(e.responseText);
                $('#submit').attr('disabled', false)
                .val(textButtonSubmit);
            }
        });
    }
});