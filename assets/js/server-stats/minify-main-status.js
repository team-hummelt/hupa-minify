

(function ($) {

    $('.minify_color.mm-color-picker').wpColorPicker();

    /**==============================================
     ========= Change SERVER STAUS Settings =========
     ================================================
     */
    let InputServerStatusFormTimeout;
    $('.send-ajax-server-status-settings').on('input propertychange change', function () {
        $('.ajax-status-spinner').html('<i class="fa fa-spinner fa-spin"></i>&nbsp; Saving...');
        const form_data = $(this).serializeObject();
        clearTimeout(InputServerStatusFormTimeout);
        InputServerStatusFormTimeout = setTimeout(function () {
            set_server_status_form_input(form_data);
        }, 1000);
    });

    function set_server_status_form_input(form_data) {
        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyServer',
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

    /**====================================
     ========= LOAD PROCESS DATEN =========
     ======================================
     */
    $(function () {

        if(minify_ajax_obj.hupa_starter_theme === '1' && minify_ajax_obj.live_statistic === '1'){
            $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyServer',
                '_ajax_nonce': minify_ajax_obj.nonce,
                'method': 'load_footer_layout',
            }, function (data) {
                if(data.status){
                    let adminFooter = document.querySelector('.admin_footer_version');
                    let statusSpan = document.createElement('span');
                    statusSpan.classList.add('real-show-status');
                    statusSpan.innerHTML = data.html;
                    adminFooter.parentNode.insertBefore(statusSpan, adminFooter);
                }
            });
        }
        let flag = false;
        function minify_do_ajax() {
            $.post(minify_ajax_obj.ajax_url, {
                    'action': 'HupaMinifyServer',
                    '_ajax_nonce': minify_ajax_obj.nonce,
                    'method': 'load_ajax_process',
                },
                function (data) {
                    let background_color;
                    let mem_background_color;
                    let ram_background_color;
                    let load = data.cpu_load;
                    $('#server-load').html(load + "%");
                    $('#cpu_load_footer').html(load + "%");
                    if (load < 10) {
                        $('#server-load').css({
                            "margin-left": "30px",
                            "color": "#444"
                        });
                    } else {
                        $('#server-load').css({
                            "margin-left": "auto",
                            "color": "#fff"
                        });
                    }
                    if (load > 80) {
                        background_color = data.bg_color_average;
                    } else if (load > 95) {
                        background_color = data.bg_color_bad;
                    } else {
                        background_color = data.bg_color_good;
                    }

                    $('#server-load-upper-div').css({
                        "width": load + '%',
                        "background-color": background_color
                    });

                    /*Fetching memory load in MB*/
                    let memory_load_mb = data.memory_usage_MB;
                    $('#mem_usage_mb').html(memory_load_mb + " MB");
                    $('#mem_usage_mb_footer').html(memory_load_mb + " MB");

                    /*Fetching memory load in percentage*/
                    let memory_usage_pos = data.memory_usage_pos;
                    $('#memory-usage-pos').html(memory_usage_pos + "%");
                    $('#memory-usage-pos-footer').html(memory_usage_pos + "%");

                    if (memory_usage_pos < 10) {
                        $('#memory-usage-pos').css({
                            "margin-left": "30px",
                            "color": "#444"
                        });
                    } else {
                        $('#memory-usage-pos').css({
                            "margin-left": "auto",
                            "color": "#fff"
                        });
                    }

                    if (memory_usage_pos > 80) {
                        mem_background_color = data.bg_color_average;
                    } else if (memory_usage_pos > 95) {
                        mem_background_color = data.bg_color_bad;
                    } else {
                        mem_background_color = data.bg_color_good;
                    }
                    $('#memory-load-upper-div').css({
                        "width": memory_usage_pos + '%',
                        "background-color": mem_background_color
                    });

                    /*Fetching RAM Usage*/
                    $('#realtime_ram_usage').html(data.used_ram);
                    $('#realtime_free_ram').html(data.free_ram);
                    $('#ram_usage_footer').html(data.used_ram);

                    /*Fetching RAM load in percentage*/
                    let ram_usage_pos = data.ram_usage_pos;
                    $('#ram-usage').html(ram_usage_pos + "%");
                    $('#ram-usage-pos-footer').html(ram_usage_pos + "%");

                    if (ram_usage_pos < 10) {
                        $('#ram-usage').css({
                            "margin-left": "30px",
                            "color": "#444"
                        });
                    } else {
                        $('#ram-usage').css({
                            "margin-left": "auto",
                            "color": "#fff"
                        });
                    }
                    if (ram_usage_pos > 80) {
                        ram_background_color = data.bg_color_average;
                    } else if (ram_usage_pos > 95) {
                        ram_background_color = data.bg_color_bad;
                    } else {
                        ram_background_color = data.bg_color_good;
                    }

                    $('#ram-usage-upper-div').css({
                        "width": ram_usage_pos + '%',
                        "background-color": ram_background_color
                    });

                    setTimeout(minify_do_ajax, data.refresh_interval); //After completion of request, time to redo it after a second
                    if (flag == false) {
                        showUptime(data.uptime);
                        flag = true;
                    }
                });
        }

        if(minify_ajax_obj.live_statistic === '1') {
            minify_do_ajax();
        }

        function showUptime(upsec) {
            let clock = $('.uptime').FlipClock(upsec, {
                clockFace: 'DailyCounter',
                countdown: false
            });
        }

        $(window).on("resize", function (event) {
            let browserWidth = $(window).width();
            let zoom;
            //console.log('width ' + browserWidth);
            if (browserWidth > 1800) {
                zoom = ((0.54 / 1920) * browserWidth);
            } else if (browserWidth > 1499 && browserWidth <= 1800) {
                zoom = ((0.68 / 1800) * browserWidth);
            } else if (browserWidth > 1252 && browserWidth <= 1499) {
                zoom = ((0.81 / 1426) * browserWidth);
            } else if (browserWidth > 943 && browserWidth <= 1252) {
                zoom = ((0.48 / 947) * browserWidth);
            } else if (browserWidth > 782 && browserWidth <= 943) {
                zoom = ((0.45 / 782) * browserWidth);
            } else {
                zoom = ((0.6 / 491) * browserWidth);
            }
            //console.log('zoom ' + zoom);
            $('.uptime').css({
                'zoom': '' + zoom + '',
                '-ms-transform': 'scale(' + zoom + ',' + zoom + ')',
                '-moz-transform': 'scale(' + zoom + ',' + zoom + ')',
                '-ms-transform-origin': '0 0',
                '-moz-transform-origin': '0 0',
                'width': '-moz-max-content'
            });
        }).resize();
    });

    $(document).on('click', 'li#wp-admin-bar-wpss-cache-purge .ab-item', function () {

        $.post(minify_ajax_obj.ajax_url, {
                'action': 'HupaMinifyServer',
                '_ajax_nonce': minify_ajax_obj.nonce,
                'method': 'minify_cache_purge',
            },
            function (data) {
                alert(data.msg);
            });
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

})(jQuery);

