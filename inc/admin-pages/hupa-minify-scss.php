<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div id="minifyThemeRoot" data-folder="<?=HUPA_MINIFY_THEME_ROOT?>"  class="card card-license shadow-sm">
            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                HUPA&nbsp; <?= __( 'Minify SCSS', 'hupa-minify' ) ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __( 'Minify', 'hupa-minify' ) ?>
                        / <span id="currentSideTitle"><?= __( 'SCSS Compiler', 'hupa-minify' ) ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __( 'SCSS Compiler', 'hupa-minify' ) ?>"
                            data-type="start"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifySCSSStart"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-css3"></i>&nbsp;
						<?= __( 'SCSS Settings', 'hupa-minify' ) ?>
                    </button>
                </div>
                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING MINIFY STARTSEITE -->
                    <div class="collapse show" id="collapseMinifySCSSStart"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">

                            <div class="d-flex align-items-center">
                                <h5 class="card-title d-flex">
                                    <i class="hupa-color d-block mt-1 icon-hupa-white"></i>&nbsp;<?= __( 'Minify SCSS Compiler Settings', 'hupa-minify' ) ?>
                                </h5>
                                <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                            </div>
                            <hr>
                            <div class="card card-body bg-light mb-2 shadow-sm">
                                <form class="send-ajax-minify-settings" action="#" method="post">
                                    <input type="hidden" name="method" value="update_scss_settings">
                                    <div class="show-form-input">
                                        <h6>Pfade einrichten</h6>
                                        <hr class="mt-0">
                                    </div>
                                    <div class="row show-form-input">
                                        <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                            <label for="inputSourceFolder"
                                                   class="form-label">
												<?= __( 'SCSS / SASS Location', 'hupa-minify' ); ?></label>
                                            <input type="text" name="source"
                                                   value="<?= get_option( 'minify_scss_source' ) ?>"
                                                   class="form-control"
                                                   id="inputSourceFolder" <?= get_option( 'minify_scss_source' ) ?: 'disabled' ?>>
                                            <button data-target="#inputSourceFolder" type="button"
                                                    class="btn-show-folder-tree btn btn-blue-outline btn-sm my-3">
                                                <i class="fa fa-folder-open-o"></i>
                                                Location auswählen
                                            </button>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                            <label for="inputDestinationFolder"
                                                   class="form-label"><?= __( 'CSS Location', 'hupa-minify' ); ?></label>
                                            <input type="text" name="destination"
                                                   value="<?= get_option( 'minify_scss_destination' ) ?>"
                                                   class="form-control"
                                                   id="inputDestinationFolder" <?= get_option( 'minify_scss_destination' ) ?: 'disabled' ?>>
                                            <button data-target="#inputDestinationFolder" type="button"
                                                    class="btn-show-folder-tree btn btn-blue-outline my-3 btn-sm">
                                                <i class="fa fa-folder-open-o"></i> Location auswählen
                                            </button>
                                        </div>
                                        <hr class="mb-2">
                                        <h6>Ausgabe Settings</h6>
                                        <hr>
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="SelectMode" class="form-label">Ausgabe</label>
                                                <select onchange="this.blur()" id="SelectMode" name="formatter_mode"
                                                        class="form-select">
                                                    <option value="expanded"
		                                                <?= get_option( 'minify_scss_formatter' ) == 'expanded' ? 'selected' : '' ?>>
                                                        expanded
                                                    </option>
                                                    <option value="compact"
														<?= get_option( 'minify_scss_formatter' ) == 'compact' ? 'selected' : '' ?>>
                                                        compact
                                                    </option>
                                                    <option value="nested"
														<?= get_option( 'minify_scss_formatter' ) == 'nested' ? 'selected' : '' ?>>
                                                        verschachtelt
                                                    </option>
                                                    <option value="compressed"
														<?= get_option( 'minify_scss_formatter' ) == 'compressed' ? 'selected' : '' ?>>
                                                        compressed
                                                    </option>
                                                    <option value="crunched"
														<?= get_option( 'minify_scss_formatter' ) == 'crunched' ? 'selected' : '' ?>>
                                                        crunched
                                                    </option>
                                                    <option value="debug"
														<?= get_option( 'minify_scss_formatter' ) == 'debug' ? 'selected' : '' ?>>
                                                        debug
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr >
                                    <div class="d-flex flex-wrap show-form-input">
                                        <div class="form-check form-switch mb-md-0 mb-3  me-3">
                                            <input class="form-check-input" name="stylesheet" type="checkbox"
                                                   role="switch"
                                                   id="SwitchStyleSheets" <?= ! get_option( 'scss_stylesheet_aktiv' ) ?: ' checked' ?>>
                                            <label class="form-check-label" for="SwitchStyleSheets">Enqueue
                                                Stylesheets erstellen</label>
                                        </div>
                                        <div class="form-check form-switch me-3">
                                            <input class="form-check-input" data-bs-toggle="collapse" data-bs-target="#collapseMapOption" name="map" type="checkbox" role="switch"
                                                   id="SwitchSourceMap" <?= ! get_option( 'scss_map_aktiv' ) ?: ' checked' ?>>
                                            <label class="form-check-label" for="SwitchSourceMap">Source Map
                                                erstellen</label>
                                        </div>
                                    </div>

                                    <div class="show-form-input">
                                        <div class="collapse <?= get_option( 'scss_map_aktiv' ) ? ' show': '' ?>" id="collapseMapOption">
                                            <hr>
                                            <div class="mb-3">
                                                <label for="SelectMapFile" class="form-label">Source Map Optionen</label>
                                                <select onchange="this.blur()" id="SelectMapFile" name="map_option"
                                                        class="form-select">
                                                    <option value="map_inline"
				                                        <?= get_option( 'minify_scss_map_option' ) == 'map_inline' ? 'selected' : '' ?>>
                                                        Inline
                                                    </option>
                                                    <option value="map_file"
				                                        <?= get_option( 'minify_scss_map_option' ) == 'map_file' ? 'selected' : '' ?>>
                                                        File
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr class="mb-2">
                                        <h6>Ausgabe <small class="small text-muted"> ( entwicklung ) </small></h6>
                                        <hr class="mt-2">
                                        <div class="form-check form-switch mb-3 me-3">
                                            <input class="form-check-input" name="line_comments_aktiv" type="checkbox"
                                                   role="switch"
                                                   id="SwitchLineComments" <?= ! get_option( 'line_comments_aktiv' ) ?: ' checked' ?>>
                                            <label class="form-check-label" for="SwitchLineComments">Zeilenkommentare
                                                anzeigen</label>
                                        </div>
                                    </div>
                                </form>
                                <div class="three-wrapper show-form-input d-none">
                                    <h6><i class="fa fa-folder-open-o"></i> Ordner auswählen</h6>
                                    <hr class="mt-1">
                                    <div id="container"></div>
                                    <hr>
                                    <div class="ordner-select">Ordnername</div>
                                    <button class="btn-select-folder btn btn-blue-outline mb-2 mt-3 btn-sm">
                                        <i class="fa fa-folder-open"></i> Ordner wählen
                                    </button>
                                    <button class="btn-show-folder-tree btn btn-light btn-sm mb-2 mt-3 btn-sm">
                                        <i class="text-danger fa fa-close"></i>
                                        abbrechen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div><!--collapse-->
                </div><!--parent-->
            </div>
            <small class="card-body-bottom" style="right: 1.5rem">Minify Version: <i
                        class="hupa-color">v<?= HUPA_MINIFY_PLUGIN_VERSION ?></i></small>
        </div>

    </div>
</div>
