<div id="comments_list_container"></div>


<script>

    $(document).ready(function(){
        get_comments_list();
    });

    function get_comments_list(){
        $.ajax({
            type: 'POST',
            url: "{{ route('feedback.get_comments_list') }}",
            data: {
                _token: '{{ csrf_token() }}',
                feedback_id: '{{ $feedbackId }}'
            },
            success: function (response, textStatus, xhr) {
                $("#comments_list_container").html(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                console.error(response);
            }
        });
    }
</script>