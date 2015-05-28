function init ()
{
    $('#button_green').click(function ()
    {
        var name  = $('#name').val();
        var email = $('#email').val();
        var text  = $('#comment').val();

        $.ajax(
        {
            url : 'mail.php',
            type : 'POST',
            data :
            {
                'name'  : name,
                'email' : email,
                'text'  : text
            },
            tryCount : 0,
            retryLimit : 3,
            success : function (json)
            {
                var response = JSON.parse(json);

                console.log(response);


                $('#name, #email, #comment').css('background', 'none');

                if ( response.isAllValid )
                {
                    $('#name, #email, #comment').val('');

                    alert(response.msg);
                }
                else 
                {
                    if ( ! response.isNameValid )
                        $('#name').css('background', 'tomato');

                    if ( ! response.isEmailValid )
                        $('#email').css('background', 'tomato');

                    if ( ! response.isTextValid )
                        $('#comment').css('background', 'tomato');
                }
            },
            error : function(xhr, textStatus, errorThrown) {
                if (textStatus === 'timeout') {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        //try again
                        $.ajax(this);
                        return;
                    }
                    return;
                }
                if (xhr.status === 500) {
                    //handle error
                } else {
                    //handle error
                }
            }
        });
    });
}
