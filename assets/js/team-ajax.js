jQuery(document).ready(function ($) {
    $('#rul-team-member-form').on('submit', function (e) {
        e.preventDefault();

        let formData = $(this).serialize();

        formData += '&nonce=' + RULTeamsAjax.nonce;
        formData += '&action=rul_save_team_member';

        $.ajax({
            type: 'POST',
            url: RULTeamsAjax.ajax_url,
            data: formData,
            success: function (response) {
                if (response.success) {
                    alert('Member saved successfully');
                    location.reload();
                } else {
                    alert('Failed to save member');
                }
            },
            error: function () {
                alert('An error occurred.');
            }
        });
    });


    $('.delete-team-member').on('click', function (e) {
        e.preventDefault();

        let memberId = $(this).data('id');
        let nonce = RULTeamsAjax.nonce;

        if (confirm('Are you sure you want to delete this member?')) {
            $.ajax({
                type: 'POST',
                url: RULTeamsAjax.ajax_url,
                data: {
                    action: 'rul_delete_team_member',
                    nonce: nonce,
                    id: memberId
                },
                success: function (response) {
                    if (response.success) {
                        alert('Member deleted successfully');
                        location.reload();
                    } else {
                        alert('Failed to delete member');
                    }
                }
            });
        }
    });
});
