<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
$sysStatus   = false;
$statusAktiv = (bool) get_option( 'server_status_aktiv' );
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
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __( 'Server Status', 'hupa-minify' ) ?>"
                            data-type="start"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyServerStatus"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-server"></i>&nbsp;
						<?= __( 'Server status', 'hupa-minify' ) ?>
                    </button>

                    <!--<button data-site="<?= __( 'Ausgabe', 'hupa-minify' ) ?>"
					        data-type="formular"
					        type="button" id="formEditCollapseBtn"
					        data-bs-toggle="collapse" data-bs-target="#collapseMinifyTwo"
					        class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
							class="fa fa-server"></i>&nbsp;
						<?= __( 'Ausgabe', 'hupa-minify' ) ?>
					</button>-->
                </div>
                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING STATUS STARTSEITE -->
                    <div class="collapse show" id="collapseMinifyServerStatus"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">

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

                        </div>
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