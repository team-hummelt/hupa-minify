<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
$statusAktiv =  get_option( 'server_status_aktiv' );
$stat = json_decode(get_option('settings_server_status'));
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                HUPA&nbsp; <?= __( 'Server Status', 'hupa-minify' ) ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Server', 'hupa-minify' ) ?>
                        / <span id="currentSideTitle"><?= __( 'Status', 'hupa-minify' ) ?></span>
                    </h5>
                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                </div>
                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING STATUS STARTSEITE -->
                    <div class="collapse show" id="collapseMinifyServerStatus"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 63vh">

							<?php if ( ! $statusAktiv ): ?>
                                <hr>
                                <h5>Server status aktivieren</h5>
                                <hr>
                                <dl>
                                    <dt>Hinweis</dt>
                                    <dd>Diese Erweiterung wurde nur für Linux-basierte Server entwickelt und getestet,
                                        daher
                                        besteht eine sehr hohe Wahrscheinlichkeit, dass es möglicherweise <b
                                                class="text-danger strong-font-weight">NICHT</b> für
                                        Windows-basierte Server funktioniert. Daher wird dringend empfohlen, die
                                        Erweiterung <span class="text-danger"> nicht zu
                                        aktivieren</span>, wenn Sie keinen Linux-basierten Server verwenden.
                                    </dd>
                                </dl>
                                <hr>
								<?php
								if ( php_uname( "s" ) == 'Linux' ) {
									$sysText   = 'success';
									$sysStatus = true;
								} else {
									$sysText   = 'danger';
									$sysStatus = false;
								}
								?>
                                <h6>Ihr System: <b class="text-<?= $sysText ?>"> <?= php_uname( "s" ) ?></b>
                                    <div class="form-text fw-normal">
										<?= php_uname() ?>
                                    </div>
                                </h6>
                                <hr>
								<?php if ( $sysStatus ): ?>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input activate-server-status" type="checkbox"
                                               role="switch" id="CheckServerAktiv">
                                        <label class="form-check-label" for="CheckServerAktiv">Erweiterung
                                            aktivieren</label>
                                    </div>
                                    <hr>
								<?php else: ?>
                                    <h5 class="text-center"><i class="text-danger fa fa-exclamation-triangle"></i>&nbsp;
                                        Diese Erweiterung kann <b class="text-danger">nicht aktiviert</b> werden!</h5>
                                    <hr>
								<?php endif; ?>
							<?php endif; ?>
                            <!---ENDE AKTIVIERUNG--->
                            <!------------------------------------------------>
	                        <?php if ( $statusAktiv ): ?>
                                <div class="form-check form-switch">
                                    <input class="form-check-input activate-server-status" type="checkbox"
                                           role="switch" id="CheckServerAktiv" <?=!get_option( 'server_status_aktiv' ) ?: ' checked'?>>
                                    <label class="form-check-label" for="CheckServerAktiv">Server Statistik aktiv</label>
                                </div>
                                <form class="send-ajax-server-status-settings" action="#" method="post">
                                    <input type="hidden" name="method" value="update_server_status_settings">
                                    <hr>
                                    <fieldset>
                                        <div class="col-xl-4 col-lg-6 col-12">
                                            <div class="mb-3">
                                                <label for="inputScriptInterval" class="form-label">Aktualisierungsintervall</label>
                                                <input type="number" name="script_interval" value="<?=$stat->refresh_interval?>" class="form-control"
                                                       id="inputScriptInterval"
                                                       aria-describedby="inputScriptIntervalHelp">
                                                <div id="inputScriptIntervalHelp" class="form-text">
							                        <?= __( 'Set the realtime script refresh interval (in ms) [1sec = 1000ms]', 'hupa-minify' ) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-1 mb-3">
                                        <h6 class="mb-3"><i class="fa fa-gears wp-blue"></i>&nbsp; Memcache Settings</h6>
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-6 col-12">
                                                <label for="InputMemHost" class="form-label">Memcache Host</label>
                                                <input type="text" name="memcache_host" placeholder="localhost" value="<?=$stat->memcache_host?>" class="form-control"
                                                       id="InputMemHost">
                                            </div>
                                            <div class="col-xl-4 col-lg-6 col-12">
                                                <label for="InputMemPort" class="form-label">Memcache Port</label>
                                                <input type="number" value="<?=$stat->memcache_port?>" placeholder="11211" name="memcache_port" class="form-control" id="InputMemPort">
                                            </div>
                                        </div>
                                        <div  class="form-text mt-3">
					                        <?= __( 'Memcached Server Host (Only if you have Memcached installed in your server)', 'hupa-minify' ) ?>
                                        </div>
                                        <hr>
				                        <?php if(get_option('ip_api_aktiv')): ?>
                                            <h6 class="mb-3"><i class="fa fa-gears wp-blue"></i>&nbsp; IP-API Pro?</h6>
                                            <label class="form-label"><?= __( 'Do you want to use the IP-API Pro key?', 'hupa-minify' ) ?></label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="use_ipapi_pro"
                                                       role="switch" data-bs-toggle="collapse" data-bs-target="#collapseShowApiProKey"
                                                       id="CheckApiProAktiv" <?= ! $stat->use_ipapi_pro ?: ' checked' ?>>
                                                <label class="form-check-label" for="CheckApiProAktiv">aktiv</label>
                                            </div>
                                            <hr>
                                            <div id="CheckApiProAktivHelp" class="form-text mt-2">
						                        <?php
						                        printf(
							                        __( 'By default, this extension uses %1$sIP-API.com%2$s free API, which allows %3$s150 requests/min%4$s. However, for busy websites this can be very little and result in %3$s503 Error%4$s if you try to do more than %3$s150 req/min%4$s. To solve this problem, you can use the %5$sPaid version of IP-API%6$s and specify your paid key below, which will allow you a %7$sUnlimited%8$s number of requests.', 'hupa-minify' ),
							                        '<a href="https://ip-api.com/" target="_blank" rel="external nofollow">',
							                        '</a>',
							                        '<code>',
							                        '</code>',
							                        '<strong class="strong-font-weight"><a href="https://signup.ip-api.com/" target="_blank" rel="external nofollow">',
							                        '</a></strong>',
							                        '<strong>',
							                        '</strong>');?>
                                            </div>
                                            <div class="collapse <?= ! $stat->use_ipapi_pro ?: ' show' ?>"
                                                 id="collapseShowApiProKey">
                                                <hr>
                                                <div class="col-xl-4 col-lg-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="InputApiProKey" class="form-label">IP-API Key</label>
                                                        <input type="text" name="api_pro_key" value="<?=$stat->ipapi_pro_key?>" class="form-control" id="InputApiProKey"
                                                               aria-describedby="InputApiProKeyHelp">
                                                        <div id="InputApiProKeyHelp" class="form-text">
									                        <?= __( 'Provide your IP-API Pro key', 'hupa-minify' ) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
				                        <?php endif; ?>
                                        <h6 class="mb-3"><i
                                                    class="fa fa-gears wp-blue"></i>&nbsp; <?= __( 'Realtime Status Bar Background Color', 'hupa-minify' ) ?>
                                        </h6>
                                        <div class="d-flex flex-wrap">
                                            <div class="mb-3 me-3">
                                                <label for="InputGoodStat"
                                                       class="form-label d-block small mb-1"> <?= __( 'Status OK', 'hupa-minify' ) ?></label>
                                                <input type="text" name="good_status_color" value="<?=$stat->bg_color_good?>"
                                                       class="mm-color-picker minify_color" id="InputGoodStat">
                                            </div>
                                            <div class="mb-3 me-3">
                                                <label for="InputCriticalStat" class="form-label small d-block mb-1">
							                        <?= __( 'Critical Status', 'hupa-minify' ) ?></label>
                                                <input type="text" name="critical_status_color" value="<?=$stat->bg_color_average?>"
                                                       class="mm-color-picker minify_color" id="InputCriticalStat">
                                            </div>
                                            <div class="mb-3 me-3">
                                                <label for="InputSuperCriticalStat" class="form-label small d-block mb-1">
							                        <?= __( 'Very Critical Status', 'hupa-minify' ) ?></label>
                                                <input type="text" name="super_critical_status_color" value="<?=$stat->bg_color_bad?>"
                                                       class="mm-color-picker minify_color" id="InputSuperCriticalStat">
                                            </div>
                                        </div>
                                        <div class="mb-3 me-3">
                                            <label for="InputFooterTextColor" class="form-label small d-block mb-1">
						                        <?= __( 'Footer Text Color', 'hupa-minify' ) ?></label>
                                            <input type="text" name="footer_text_color" value="<?=$stat->footer_text_color?>"
                                                   class="mm-color-picker minify_color" id="InputFooterTextColor">
                                        </div>
                                    </fieldset>
                                </form>

	                        <?php endif; ?>
                           <!------------------------------------------------->
                        </div> <!--card-->
                    </div><!--collapse-->
                </div><!--parent-->
            </div>
            <small class="card-body-bottom" style="right: 1.5rem">Minify Version: <i
                        class="hupa-color">v<?= HUPA_MINIFY_PLUGIN_VERSION ?></i></small>
        </div>
    </div>
</div>
<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>