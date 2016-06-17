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
        $('#result').html('')
        .addClass('loading');
        $('#submit').attr('disabled', true)
        .val(textButtonWait);
        $.ajax({
            url: 'function.php',
            data: { 'isAjax': true },
            dataType: 'json',
            type: 'POST',
            success: function(d){
                if( d.status ) {
                    $('#result').html(
                        "<pre>\n"
                      + d.top20Word+"\n"
                      + 'Time Execution Analysis: '+d.timeExecAnalysis20Word+"sec\n"
                      + 'Time Execution Query: '+d.timeExecQuery20Word+"sec\n"
                      + 'Time Execution Build Table: '+d.timeExecBuildTable20Word+"sec\n\n"
                      + d.top20RuChar+"\n"
                      + 'Time Execution Analysis: '+d.timeExecAnalysis20RuChar+"sec\n"
                      + 'Time Execution Query: '+d.timeExecQuery20RuChar+"sec\n"
                      + 'Time Execution Build Table: '+d.timeExecBuildTable20RuChar+"sec\n\n"
                      + '============================'+"\n\n"
                      + 'Time Execution TOTAL: '+d.timeExecTotal+'sec'
                      + '</pre>'
                    );
                    console.log('true');
                    $('#submit').attr('disabled', false)
                    .val(textButtonSubmit);
                } else {
                    console.log('false');
                    $('#result').html('Error on server');
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
            },
            complete: function(){
                $('#result').removeClass('loading');
            }
        });
    }
});