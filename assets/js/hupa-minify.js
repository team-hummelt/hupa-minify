let collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
let resetMsgAlert = document.getElementById("reset-msg-alert");
//Ajax Spinner
let ajaxFormSpinner = document.querySelectorAll(".ajax-status-spinner");
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



});
