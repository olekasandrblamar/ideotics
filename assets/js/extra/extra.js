(function($) {
    "use strict";

    let cookies = document.querySelector('.cookies');
    if (cookies) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                cookies.classList.add('show');
            }, 1000);
        });
    }

    let acceptCookie = $('#acceptCookie'),
        cookieDiv = $('.cookies');
    acceptCookie.on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: getConfig.baseURL + '/cookie/accept',
            type: 'get',
            dataType: "JSON",
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    cookieDiv.remove();
                    toastr.success(response.success);
                }
            },
        });
    });

    let changeLanguage = $('.vr__change__language');
    changeLanguage.on('change', function() {
        let langURL = $(this).find(':selected').data('link');
        window.location.href = langURL;
    });

    let loadModalBtn = document.querySelector('#loadModalBtn');
    if (loadModalBtn) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                loadModalBtn.click();
                loadModalBtn.remove();
            }, 1000);
        });
        document.querySelector('#load-modal .btn-close').onclick = () => {
            $.ajax({
                url: getConfig.baseURL + '/popup/close',
                type: 'get',
                dataType: "JSON",
                success: function() {
                    $('load-modal').remove();
                },
            });
        };
    }

    let multipleSelectDeleteForm = $('.multiple-select-delete-form'),
        multipleSelectDeleteIds = $('.multiple-select-delete-ids'),
        multipleSelectCheckAll = $('.multiple-select-check-all'),
        multipleSelectCheckbox = $('.multiple-select-checkbox');
    if (multipleSelectCheckAll.length) {
        var multipleSelectDeleteIdsArr = [];
        multipleSelectCheckAll.on('click', function() {
            if ($(this).is(':checked', true)) {
                multipleSelectCheckbox.prop('checked', true);
                multipleSelectPushDeleteIds();
                multipleSelectDeleteForm.removeClass('d-none');
            } else {
                multipleSelectRemoveDeleteIds();
                multipleSelectCheckbox.prop('checked', false);
                multipleSelectDeleteForm.addClass('d-none');
            }
        });
        multipleSelectCheckbox.on('click', function() {
            multipleSelectPushDeleteIds()
            if ($('.multiple-select-checkbox:checked').length == multipleSelectCheckbox.length) {
                multipleSelectCheckAll.prop('checked', true);
            } else {
                multipleSelectCheckAll.prop('checked', false);
            }
            if ($(this).is(':checked', true)) {
                multipleSelectDeleteForm.removeClass('d-none');
            } else {
                if ($('.multiple-select-checkbox:checked').length == 0) {
                    multipleSelectDeleteForm.addClass('d-none');
                }
            }
        });
        let multipleSelectPushDeleteIds = () => {
            multipleSelectDeleteIdsArr = [];
            $(".multiple-select-checkbox:checked").each(function() {
                multipleSelectDeleteIdsArr.push($(this).attr('data-id'));
            });
            multipleSelectDeleteIds.val(multipleSelectDeleteIdsArr);
        }
        let multipleSelectRemoveDeleteIds = () => {
            multipleSelectDeleteIdsArr = [];
            multipleSelectDeleteIds.val(multipleSelectDeleteIdsArr);
        }
    }

})(jQuery);