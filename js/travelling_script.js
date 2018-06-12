$(function() {
    $('#optimize')
        .mouseenter(function() {
       $(this).fadeTo(100, 0.7);
       $(this).attr('id', 'detect-hover');
    })
        .mouseleave(function() {
       $(this).fadeTo(100, 1);
       $(this).attr('id', 'detect');
    });

    $('#optimize').click(function(event) {
        url                         = './genetic_conclusion.php';
        iteration_number            = $('#iteration_number').val();
        data                        = {'iteration_number': iteration_number};

        $("#text-result").html('<span class="text-secondary">Loading...</span>');
        $("#img-result").attr('src', 'img/loading.gif');

        $.ajax({
            url: url,
            data: data,
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                $("#conclusion-text").html(data['text']);
            }
        });
    });
});