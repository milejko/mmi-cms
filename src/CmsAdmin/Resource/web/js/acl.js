$(document).ready(function () {
    initAcl();
});

function initAcl() {
    $('.rule-select').change(
            function () {
                $.post(
                        "/?module=cmsAdmin&controller=acl&action=update&id=" + $(this).attr('id'),
                        {selected: $(this).val()},
                        function (result) {
                            if (result != '1') {
                                alert(result);
                            }
                        }
                );
            }
    )

    $('a.remove-rule').click(
            function ()
            {
                var id = $(this).attr('id');
                id = id.split('-');
                $.get(
                        "/?module=cmsAdmin&controller=acl&action=delete&id=" + id[2],
                        function (result) {
                            if (result != '1') {
                                alert(result);
                            } else {
                                $('#rule-row-' + id[2]).remove();
                            }
                        }
                );
            }
    );

}