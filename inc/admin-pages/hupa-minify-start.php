<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

?>
<div class="wp-bs-starter-wrapper">

    <div class="container">
        <div class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                HUPA&nbsp; <?= __( 'Minify', 'hupa-minify' ) ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Minify', 'hupa-minify' ) ?>
                        / <span id="currentSideTitle"><?= __( 'Options', 'hupa-minify' ) ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __( 'Options', 'hupa-minify' ) ?>"
                            data-type="start"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyStartSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-wrench"></i>&nbsp;
						<?= __( 'Options', 'hupa-minify' ) ?>
                    </button>

                    <!--<button data-site="<?= __( 'Create | Edit', 'hupa-minify' ) ?>"
                            data-type="formular"
                            type="button" id="formEditCollapseBtn"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyTwo"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-align-justify"></i>&nbsp;
						<?= __( 'Create | Edit', 'hupa-minify' ) ?>
                    </button>-->
                </div>

                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING MINIFY STARTSEITE -->
                    <div class="collapse show" id="collapseMinifyStartSite"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <h5 class="card-title">
                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'HUPA Minify', 'hupa-minify' ) ?>
                                Einstellungen
                            </h5>
                            <hr>
                            <div class="col-lg-12 pt-2">
                                <h6>
                                    <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Minify active', 'hupa-minify' ); ?>
                                </h6>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="min_aktiv" type="checkbox"
                                           id="CheckMinActive"
                                           aria-describedby="CheckVersionActiveHelp" <?= (int) ! get_option( 'minify_aktiv' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckMinActive">
										<?= __( 'active', 'hupa-minify' ) ?></label>
                                </div>
                            </div>
                            <hr>
                            <fieldset disabled>

                                <div class="d-lg-flex d-block pb-3">
                                <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                    <input class="form-check-input" name="css_aktiv" type="checkbox"
                                           id="CheckMinActive"
                                           aria-describedby="CheckVersionActiveHelp" <?= (int) ! get_option( 'minify_css_aktiv' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckMinActive">
										<?= __( 'CSS active', 'hupa-minify' ) ?></label>
                                </div>

                                <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                    <input class="form-check-input" name="js_aktiv" type="checkbox"
                                           id="CheckMinActive"
                                           aria-describedby="CheckVersionActiveHelp" <?= (int) ! get_option( 'minify_js_aktiv' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckMinActive">
										<?= __( 'JS active', 'hupa-minify' ) ?></label>
                                </div>

                                <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                    <input class="form-check-input" name="html_aktiv" type="checkbox"
                                           id="CheckMinActive"
                                           aria-describedby="CheckVersionActiveHelp" <?= (int) ! get_option( 'minify_html_aktiv' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckMinActive">
			                            <?= __( 'HTML active', 'hupa-minify' ) ?></label>
                                </div>
                            </div>

                            </fieldset>
                            <h5 class="card-title  py-3 bg-hupa">
                                <i class="fa fa-wrench ms-3"></i>&nbsp;<?= __( 'Other options', 'hupa-minify' ) ?>
                            </h5>
                            <hr>
                            <div class="col-lg-12 pt-2">
                                <h6>
                                    <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Remove WordPress Version', 'hupa-minify' ); ?>
                                </h6>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="version_aktiv" type="checkbox"
                                           id="CheckVersionActive"
                                           aria-describedby="CheckVersionActiveHelp" <?= (int) ! get_option( 'minify_wp_version' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckVersionActive">
										<?= __( 'Remove version', 'hupa-minify' ) ?></label>
                                </div>
                                <div id="CheckVersionActiveHelp" class="form-text">
									<?= __( 'If active, the WordPress version is <b>hidden</b> in the FrontEnd.', 'hupa-minify' ) ?>
                                </div>
                            </div>
                            <hr>

                            <div class="col-lg-12 pt-2">
                                <h6>
                                    <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Remove emoji', 'hupa-minify' ); ?>
                                </h6>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="emoji_aktiv" type="checkbox"
                                           id="CheckEmojiActive"
                                           aria-describedby="CheckEmojiActiveHelp" <?= (int) ! get_option( 'minify_wp_emoji' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckEmojiActive">
										<?= __( 'remove', 'hupa-minify' ) ?></label>
                                </div>
                                <div id="CheckEmojiActiveHelp" class="form-text">
									<?= __( 'If this option is enabled the emoji source code will be <b>removed</b> from the front end.', 'hupa-minify' ) ?>
                                </div>
                            </div>
                            <hr>

                            <div class="col-lg-12 pt-2">
                                <h6>
                                    <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Remove WP block CSS', 'hupa-minify' ); ?>
                                </h6>
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" name="css_aktiv" type="checkbox"
                                           id="CheckCSSBlockActive"
                                           aria-describedby="CheckCSSBlockActiveHelp" <?= (int) ! get_option( 'minify_wp_block_css' ) ?: 'checked' ?>>
                                    <label class="form-check-label" for="CheckCSSBlockActive">
										<?= __( 'remove', 'hupa-minify' ) ?></label>
                                </div>
                                <div id="CheckCSSBlockActiveHelp" class="form-text">
									<?= __( 'If this option is activated, the CSS Gutenberg editor source code and WooCommerce CSS code is removed from the front end.', 'hupa-minify' ) ?>
                                </div>
                            </div>

                            <hr>

                        </div>
                    </div><!--Startseite-->

                    <!--//TODO JOB WARNING SITE TWO-->
                    <div class="collapse" id="collapseMinifyTwo"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title">
                                    <i class="font-blue fa fa-gears"></i>&nbsp;<?= __( 'SMTP Settings', 'hupa-minify' ) ?>
                                </h5>
                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                            </div>
                            <hr>

                        </div>
                    </div>
                </div>
            </div><!--card-->
            <small class="card-body-bottom" style="right: 1.5rem">Minify Version: <i
                        class="hupa-color">v<?= HUPA_MINIFY_PLUGIN_VERSION ?></i></small>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="formDeleteModal" tabindex="-1" aria-labelledby="formDeleteModal"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                class="text-danger fa fa-times"></i>&nbsp; Abbrechen
                    </button>
                    <button type="button" data-bs-dismiss="modal"
                            class="btn-delete-form btn btn-danger">
                        <i class="fa fa-trash-o"></i>&nbsp; löschen
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!--Modal-->
    <div class="modal fade" id="btnIconModal" tabindex="-1" aria-labelledby="btnIconModal"
         aria-hidden="true">
        <div class="modal-dialog modal-xl modal-fullscreen-xl-down modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-hupa">
                    <h5 class="modal-title"
                        id="exampleModalLabel"><?= __( 'hupa-minify', 'hupa-minify' ); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="icon-grid"></div>
                    <div id="email-template"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"><i
                                class="text-danger fa fa-times"></i>&nbsp; Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>