function updatePrice(){
    var price = 0;
    
    $('select.addSelect option:selected').each(function() {
        price = price + parseFloat($(this).attr('price'));
    });

    document.getElementById('price').value = price;
    document.getElementById('price').innerHTML = price + " €";
}

function toggleIngredientList() {
    $('select.addSelect').each(function() {
        if ( $(this).is(':disabled') ) {
            $(this).prop('disabled', false);
        } else {
            $(this).prop('disabled', true);
        }
    });
}

$(document).ready(function() {
    $('form.destroy-form').on('submit', function(submit) {
        var confirm_message = $(this).attr('data-confirm');

        if ( !confirm(confirm_message) ){
          submit.preventDefault();
        }
    });
});

$(document).ready(function() {
    updatePrice();
});

