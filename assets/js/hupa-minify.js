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

jQuery(document).ready(function ($) {

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

    /* $(document).on('click', '#CheckMinActive', function () {
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
     });*/


    $(document).on('click', '.form-check-input, .btn', function () {
        $(this).trigger('blur')
    });

    $(document).on('click', '#compilerAktiv', function () {

        let fieldSet = $('#scssConfig');
        if($(this).prop('checked')){
            fieldSet.prop('disabled', false);
        } else {
            fieldSet.prop('disabled', true);
        }
        let scssAktiv;
        $(this).prop('checked') ? scssAktiv = 1 : scssAktiv = 0;
        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyHandle',
                '_ajax_nonce': minify_ajax_obj.nonce,
                'method': 'set_scss_compiler_aktiv',
                'active': scssAktiv,
            },
            function (data) {
                if (data.status) {
                    success_message(data.msg);
                } else {
                    warning_message(data.msg);
                }
            });
    });

    $(document).on('click', '.clear-cache', function () {
        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyHandle',
                '_ajax_nonce': minify_ajax_obj.nonce,
                'method': 'minify_clear_cache',
            },
            function (data) {
                if (data.status) {
                    success_message(data.msg);
                } else {
                    warning_message(data.msg);
                }
            });
    });

    $(document).on('change', '#inputSelectCache', function () {
        $(this).trigger('blur');
        let parentCollapse = $(this).attr('data-bs-target');

        if ($(this).val() === '3') {
            console.log(parentCollapse)
            $(parentCollapse).addClass('show');
        } else {
            $(parentCollapse).removeClass('show');
            //memCollapse.classList.remove('show')
        }
    });

    $(document).on('click', '.activate-server-status', function () {

        let activate;
        if ($(this).prop('checked')) {
            activate = 'on';
        } else {
            activate = '';
        }
            $.post(minify_ajax_obj.ajax_url, {
                    'action': 'HupaMinifyHandle',
                    '_ajax_nonce': minify_ajax_obj.nonce,
                    'method': 'activate_server_status',
                    'activate': activate,
                },
                function (data) {
                    if (data.status) {
                        window.location.reload(true);
                    } else {
                        warning_message(data.msg);
                    }
                });
    });
    $(document).on('click', '#SwitchEchtZeitAktiv', function () {
        let optionFooter = $('#SwitchFooterAktiv');
        if($(this).prop('checked')){
            optionFooter.prop('disabled',false);
        } else {
            optionFooter.prop('disabled', true);
            optionFooter.prop('checked',false);
        }
    });

    $(document).on('dblclick', '.btn-reset-double', function () {
        let resetBtn = $('.btnResetBtnMinify');
        resetBtn.removeClass('d-none');
    });

    $(document).on('click', '.btnResetBtnMinify', function () {
        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyHandle',
                '_ajax_nonce': minify_ajax_obj.nonce,
                'method': $(this).attr('data-method'),
            },
            function (data) {
            switch (data.method){
                case 'reset_minify_settings':
                    window.location.reload(true);
                    break;
                case'change_ip_api_aktiv':
                    let ipApiBtn = $('#changeIpApi');
                    if(data.ip_api) {
                        ipApiBtn.html('IP-API deaktivieren');
                    } else {
                        ipApiBtn.html('IP-API aktivieren');
                    }
                    break;
                case 'change_statistik_menu':
                    let statisticMenu = $('#changeShowServeMenu');
                    if(data.show_menu) {
                        statisticMenu.html('Statistik-Menu deaktivieren');
                    } else {
                        statisticMenu.html('Statistik-Menu aktivieren');
                    }
                    break;
            }

            });
    });


    /**+++++++++++++++++++++++++++++++++++++
     *+++++++ Change LOG Settings ++++++++++
     *++++++++++++++++++++++++++++++++++++++
     */
    let InputMinifyFormTimeout;
    $('.send-ajax-minify-settings').on('input propertychange change', function () {
        $('.ajax-status-spinner').html('<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
        let form_data = $(this).serializeObject();
        clearTimeout(InputMinifyFormTimeout);
        InputMinifyFormTimeout = setTimeout(function () {
            set_settings_form_input(form_data);
        }, 1000);
    });

    function set_settings_form_input(form_data) {

        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyHandle',
                '_ajax_nonce': minify_ajax_obj.nonce,
                data: form_data
            },
            function (data) {
                if (data.spinner) {
                    show_ajax_spinner(data);
                    return false;
                }
                if (data.msg) {
                    if (data.status) {
                        success_message(data.msg);
                    } else {
                        warning_message(data.msg);
                    }
                }
            });
    }

    $(document).on('click', '.btn-select-folder', function () {
        let target = $(this).attr('data-target');
        let source = $(this).attr('data-source');
        $(target).val(source);
        if (source) {
            $(target).prop('disabled', false);
        } else {
            $(target).prop('disabled', true);
        }
        let form = $(target).parents('form');
        let formData = form.serializeObject();

        $('.show-form-input').toggleClass('d-none');
        clearTimeout(InputMinifyFormTimeout);
        InputMinifyFormTimeout = setTimeout(function () {
            set_settings_form_input(formData);
        }, 1000);
    });

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
