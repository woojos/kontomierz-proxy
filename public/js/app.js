var $input = $(".typeahead");

$input.change(function() {
    var current = $input.typeahead("getActive");
    if (current) {
        // Some item from your model is active!
        if (current.name == $input.val()) {
            //$input.attr('data-id', current.id);
        } else {
            //$input.attr('data-id', 0);
        }
    } else {
        // Nothing is active so it is a new value (or maybe empty value)
    }
});

var rowNum = 20;

$('.submit-btn').click(function(){

    var requestData = {};

    requestData.data = $('#date').val();
    requestData.wallet = $('#wallet').val();
    requestData.expenses = [];

    for (i = 1; i <= rowNum; i++) {

        var category = $('#category' + i).val();
        var amount = $('#amount' + i).val();

        if (category.length > 0 && amount > 0) {
            requestData.expenses.push({
                'category' : category,
                'amount' : amount,
                'desc' : $('#description'+i).val()
            });
        }
    }

    $.ajax({
        type: "POST",
        url: '/',
        data: JSON.stringify(requestData),
        dataType: 'json',
        success: function (res) {

            for (i = 0; i < res.length; i++) {
                console.log(res[i].status);
                if ('failed' == res[i].status) {
                    $('#row' + i).addClass('danger');
                } else {
                    $('#row' + i).addClass('success');
                }
            }
        }
    });

});

$('.reset').click(function(){

    var r = confirm("Czy na pewno chcesz wyczyścić formularz ?");

    if (r == true) {

        for (i = 1; i <= rowNum; i++) {

            $('#category' + i).val('');
            $('#amount' + i).val('');
            $('#description'+i).val('');

            $('#row' + (i-1)).removeClass('danger');
            $('#row' + (i-1)).removeClass('success');

            $('#total-top').html('0.0');
        }

    }
});

$('.calculate').click(function(){
   var total = 0;

    $('.amount').each(
        function(index, el) {

            partial = parseFloat($(el).val());
            if (partial) {
                total += partial;
            }
        });

    $('#total-top').html(total.toFixed(2));
});