$(function() {
    if (!estVide($('#security').val())) {
        var security = $('#security').val();

        var value;
        var color;
        if (security == 'veryBad') {
            value = 25;
            color = '#E90716';
        } else if (security == 'bad') {
            value = 50;
            color = '#FE9001';
        } else if (security == 'good') {
            value = 75;
            color = '#58DB00';
        } else if (security == 'veryGood') {
            value = 100;
            color = 'green';
        }

        $('#progressbar').progressbar();
        $('#progressbar').animate({
            width: parseInt($("#bigbar").css('width')) * value / 100
        }, {
            duration: 3000 * value / 100,
            easing: "easeOutBack"
        });
        
        $(".ui-progressbar").css('background', color);
    }

    function estVide(nom) {
        if (nom !== undefined && nom !== null && nom !== '' && nom !== false) {
            return false;
        }
        return true;
    }
});


