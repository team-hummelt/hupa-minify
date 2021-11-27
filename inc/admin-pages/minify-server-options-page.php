<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
$stat = json_decode( get_option( 'settings_server_status' ) );
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
				<?= __( 'Change the Server Stats Settings', 'hupa-minify' ) ?> </h5>
            <div class="card-body pb-4" style="min-height: 60vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i>
                        <span id="currentSideTitle"><?= __( 'Server Stats Settings', 'hupa-minify' ) ?></span>
                    </h5>
                </div>
                <hr>
                <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                    <div class="d-flex align-items-center">
                        <h6>Einstellungen</h6>
                        <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                    </div>
                    <form class="send-ajax-server-status-settings" action="#" method="post">
                        <input type="hidden" name="method" value="update_server_status_settings">
                        <hr>
                        <fieldset>
                            <div class="d-flex flex-wrap show-form-input">
                                <div class="form-check form-switch mb-3 me-3">
                                    <input class="form-check-input" name="echtzeit_aktiv" type="checkbox"
                                           role="switch"
                                           id="SwitchEchtZeitAktiv" <?= ! get_option( 'echtzeit_statistik_aktiv' ) ?: ' checked' ?>>
                                    <label class="form-check-label" for="SwitchEchtZeitAktiv"><b
                                                class="strong-font-weight"> Echtzeit</b> Statistik aktiv</label>
                                </div>

                                <div class="form-check form-switch mb-3 me-3">
                                    <input class="form-check-input" name="server_footer_aktiv" type="checkbox"
                                           role="switch"
                                           id="SwitchFooterAktiv" <?= ! get_option( 'server_footer_aktiv' ) ?: ' checked' ?> <?= get_option( 'echtzeit_statistik_aktiv' ) ?: ' disabled' ?>>
                                    <label class="form-check-label" for="SwitchFooterAktiv"><b
                                                class="strong-font-weight">Footer</b> Statistik aktiv</label>
                                </div>
                                <div class="form-check form-switch mb-3 me-3">
                                    <input class="form-check-input" name="server_dashboard_aktiv" type="checkbox"
                                           role="switch"
                                           id="SwitchDashboardAktiv" <?= ! get_option( 'server_dashboard_aktiv' ) ?: ' checked' ?>>
                                    <label class="form-check-label" for="SwitchDashboardAktiv"><b
                                                class="strong-font-weight">Dashboard</b>-Widget aktiv</label>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <h6>Menü anzeigen</h6>
                            <hr>
                            <div class="form-check form-switch mb-3 me-3">
                                <input class="form-check-input" name="php_menu_aktiv" type="checkbox"
                                       role="switch"
                                       id="SwitchPhpMenuAktiv" <?= ! get_option( 'php_menu_aktiv' ) ?: ' checked' ?>>
                                <label class="form-check-label" for="SwitchPhpMenuAktiv"><b class="strong-font-weight">
                                        PHP</b> Informationen</label>
                            </div>
                            <div class="form-check form-switch mb-3 me-3">
                                <input class="form-check-input" name="sql_menu_aktiv" type="checkbox" role="switch"
                                       id="SwitchSqlMenuAktiv" <?= ! get_option( 'sql_menu_aktiv' ) ?: ' checked' ?>>
                                <label class="form-check-label" for="SwitchSqlMenuAktiv"><b class="strong-font-weight">
                                        SQL</b> Informationen</label>
                            </div>
                            <div class="form-check form-switch mb-3 me-3">
                                <input class="form-check-input" name="memcache_menu_aktiv" type="checkbox" role="switch"
                                       id="SwitchMemCacheMenuAktiv" <?= ! get_option( 'memcache_menu_aktiv' ) ?: ' checked' ?>>
                                <label class="form-check-label" for="SwitchMemCacheMenuAktiv"><b
                                            class="strong-font-weight">Memcache</b> Informationen</label>
                            </div>
                            <hr class="mt-0">
                            <div class="col-xl-4 col-lg-6 col-12">
                                <div class="mb-0">
                                    <label for="inputScriptInterval" class="form-label">Aktualisierungsintervall</label>
                                    <input type="number" name="script_interval" value="<?= $stat->refresh_interval ?>"
                                           class="form-control"
                                           id="inputScriptInterval"
                                           aria-describedby="inputScriptIntervalHelp">
                                </div>
                            </div>
                            <div id="inputScriptIntervalHelp" class="form-text mb-3">
		                        <?= __( 'Set the realtime script refresh interval (in ms) [1sec = 1000ms]', 'hupa-minify' ) ?>
                            </div>
                            <hr class="mt-1 mb-3">
                            <h6 class="mb-3"><i class="fa fa-gears wp-blue"></i>&nbsp; Memcache Settings</h6>
                            <div class="row">
                                <div class="col-xl-4 col-lg-6 col-12">
                                    <label for="InputMemHost" class="form-label">Memcache Host</label>
                                    <input type="text" name="memcache_host" placeholder="localhost"
                                           value="<?= $stat->memcache_host ?>" class="form-control"
                                           id="InputMemHost">
                                </div>
                                <div class="col-xl-4 col-lg-6 col-12">
                                    <label for="InputMemPort" class="form-label">Memcache Port</label>
                                    <input type="number" value="<?= $stat->memcache_port ?>" placeholder="11211"
                                           name="memcache_port" class="form-control" id="InputMemPort">
                                </div>
                            </div>
                            <div class="form-text mt-3">
								<?= __( 'Memcached Server Host (Only if you have Memcached installed in your server)', 'hupa-minify' ) ?>
                            </div>
                            <hr>
							<?php if ( get_option('ip_api_aktiv') ): ?>
                                <h6 class="mb-3"><i class="fa fa-gears wp-blue"></i>&nbsp; IP-API Pro?</h6>
                                <label class="form-label"><?= __( 'Do you want to use the IP-API Pro key?', 'hupa-minify' ) ?></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="use_ipapi_pro"
                                           role="switch" data-bs-toggle="collapse"
                                           data-bs-target="#collapseShowApiProKey"
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
										'</strong>' ); ?>
                                </div>
                                <div class="collapse <?= ! $stat->use_ipapi_pro ?: ' show' ?>"
                                     id="collapseShowApiProKey">
                                    <hr>
                                    <div class="col-xl-4 col-lg-6 col-12">
                                        <div class="mb-3">
                                            <label for="InputApiProKey" class="form-label">IP-API Key</label>
                                            <input type="text" name="api_pro_key" value="<?= $stat->ipapi_pro_key ?>"
                                                   class="form-control" id="InputApiProKey"
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
                                    <input type="text" name="good_status_color" value="<?= $stat->bg_color_good ?>"
                                           class="mm-color-picker minify_color" id="InputGoodStat">
                                </div>
                                <div class="mb-3 me-3">
                                    <label for="InputCriticalStat" class="form-label small d-block mb-1">
										<?= __( 'Critical Status', 'hupa-minify' ) ?></label>
                                    <input type="text" name="critical_status_color"
                                           value="<?= $stat->bg_color_average ?>"
                                           class="mm-color-picker minify_color" id="InputCriticalStat">
                                </div>
                                <div class="mb-3 me-3">
                                    <label for="InputSuperCriticalStat" class="form-label small d-block mb-1">
										<?= __( 'Very Critical Status', 'hupa-minify' ) ?></label>
                                    <input type="text" name="super_critical_status_color"
                                           value="<?= $stat->bg_color_bad ?>"
                                           class="mm-color-picker minify_color" id="InputSuperCriticalStat">
                                </div>
                            </div>
                            <div class="mb-3 me-3">
                                <label for="InputFooterTextColor" class="form-label small d-block mb-1">
									<?= __( 'Footer Text Color', 'hupa-minify' ) ?></label>
                                <input type="text" name="footer_text_color" value="<?= $stat->footer_text_color ?>"
                                       class="mm-color-picker minify_color" id="InputFooterTextColor">
                            </div>
                        </fieldset>
                    </form>
                </div>
                <small class="card-body-bottom" style="right: 1.5rem">Minify Version: <i
                            class="hupa-color">v<?= HUPA_MINIFY_PLUGIN_VERSION ?></i></small>
            </div>
        </div>
    </div>
    <div id="snackbar-success"></div>
    <div id="snackbar-warning"></div>