let collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
let resetMsgAlert = document.getElementById("reset-msg-alert");
//Ajax Spinner
let ajaxFormSpinner = document.querySelectorAll(".ajax-status-spinner");

let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
});


/**======================================
 ========== AJAX SPINNER SHOW  ==========
 ========================================
 */
function show_ajax_spinner(data) {
    let msg = '';
    if (data.status) {
        msg = '<i class="text-success fa fa-check"></i>&nbsp; Saved! Last: ' + data.msg;
    } else {
        msg = '<i class="text-danger fa fa-exclamation-triangle"></i>&nbsp; ' + data.msg;
    }
    let spinner = Array.prototype.slice.call(ajaxFormSpinner, 0);
    spinner.forEach(function (spinner) {
        spinner.innerHTML = msg;
    });
}

jQuery(function ($) {

    /**================================================
     ========== TOGGLE FORMULAR COLLAPSE BTN  ==========
     ===================================================
     */
    let formularColBtn = document.querySelectorAll("button.btn-formular-collapse");
    if (formularColBtn) {
        let formCollapseEvent = Array.prototype.slice.call(formularColBtn, 0);
        formCollapseEvent.forEach(function (formCollapseEvent) {
            formCollapseEvent.addEventListener("click", function () {
                //Spinner hide
                if (resetMsgAlert) {
                    resetMsgAlert.classList.remove('show');
                }

                if (ajaxFormSpinner) {
                    let spinnerNodes = Array.prototype.slice.call(ajaxFormSpinner, 0);
                    spinnerNodes.forEach(function (spinnerNodes) {
                        spinnerNodes.innerHTML = '';
                    });
                }
                this.blur();
                if (this.classList.contains("active")) return false;
                let siteTitle = document.getElementById("currentSideTitle");
                siteTitle.innerText = this.getAttribute('data-site');
                let btnType = this.getAttribute('data-type');
                switch (btnType) {
                    case 'table':

                        break;
                    case'formular':

                        break;
                    case'posteingang':

                        break;
                }
                remove_active_btn();
                this.classList.add('active');
                this.setAttribute('disabled', true);
            });
        });

        function remove_active_btn() {
            for (let i = 0; i < formCollapseEvent.length; i++) {
                formCollapseEvent[i].classList.remove('active');
                formCollapseEvent[i].removeAttribute('disabled');
            }
        }
    }

    $(document).on('click', '#CheckMinActive', function () {
        let field = $('.minifyFieldset');
        if ($(this).prop('checked')) {
            field.prop('disabled', false);
        } else {
            field.prop('disabled', true);
        }

        let wpCore = $('.wpCoreField');
        let jsGroup = $('#CheckJSGroupsActive');
        let jsActive = $('#CheckJSActive');
        if ($(this).prop('checked') && jsActive.prop('checked')) {
            if (jsGroup.prop('checked')) {
                wpCore.prop('disabled', false);
            } else {
                wpCore.prop('disabled', true);
            }
        } else {
            wpCore.prop('disabled', true);
        }
    });

    $(document).on('click', '.groupActive', function () {
        let groupId = $(this).attr('data-id');
        if ($(this).prop('checked')) {
            $('#' + groupId).prop('disabled', false);
        } else {
            $('#' + groupId).prop('disabled', true);
        }

        if (groupId === 'CheckJSGroupsActive') {
            let jsGroupCheck = $('#CheckJSGroupsActive');
            let jsFieldCore = $('.wpCoreField');
            if ($(this).prop('checked') && jsGroupCheck.prop('checked')) {

                jsFieldCore.prop('disabled', false);
            } else {
                jsFieldCore.prop('disabled', true);
            }
        }
    });


    $(document).on('click', '#CheckJSGroupsActive', function () {
        let wpJsCore = $('.wpCoreField');
        if ($(this).prop('checked')) {
            wpJsCore.prop('disabled', false);
        } else {
            wpJsCore.prop('disabled', true);
        }
    });


    $(document).on('click', '.form-check-input, .btn', function () {
        $(this).trigger('blur')
    });

    $(document).on('dblclick', '.clickPathFolder', function () {
        $(this).next().prop('disabled', false);
    });

    $(document).on('change', '#inputSelectCache', function () {
        $(this).trigger('blur');
        let parentCollapse = $(this).attr('data-bs-target');
        if($(this).val() === '2') {
            $(parentCollapse).addClass('show');
        } else {
            $(parentCollapse).removeClass('show');
            //memCollapse.classList.remove('show')
        }
    });


    /**+++++++++++++++++++++++++++++++++++++
     *+++++++ Change LOG Settings ++++++++++
     *++++++++++++++++++++++++++++++++++++++
     */
    let InputMinifyFormTimeout;
    $('#send-ajax-minify-settings').on('input propertychange change', function () {
        $('.ajax-status-spinner').html('<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
        const form_data = $(this).serializeObject();
        clearTimeout(InputMinifyFormTimeout);
        InputMinifyFormTimeout = setTimeout(function () {
            set_settings_form_input(form_data);
        }, 1000);
    });

    function set_settings_form_input(form_data) {
        $.post(minify_ajax_obj.ajax_url, {
                method: 'update_minify_settings',
                'action': 'HupaMinifyHandle',
                '_ajax_nonce': minify_ajax_obj.nonce,
                data: form_data
            },
            function (data) {
                if (data.spinner) {
                    show_ajax_spinner(data);
                    return false;
                }
                if(data.msg) {
                    if (data.status) {
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                }
            });
    }



    function warning_message(msg) {
        let x = document.getElementById("snackbar-warning");
        $("#snackbar-warning").html(msg);
        x.className = "show";
        setTimeout(function () {
            x.className = x.className.replace("show", "");
        }, 5000);
    }

    function success_message(msg) {
        let x = document.getElementById("snackbar-success");
        x.innerHTML = msg;
        x.className = "show";
        setTimeout(function () {
            x.className = x.className.replace("show", "");
        }, 3000);
    }


    $.fn.serializeObject = function () {
        let o = {};
        let a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});
