<?php
defined('ABSPATH') or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify SCSS
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
?>
<div class="wp-bs-starter-wrapper">
    <div class="container">
        <div id="minifyThemeRoot" data-folder="<?= HUPA_MINIFY_THEME_ROOT ?>" class="card card-license shadow-sm">


            <h5 class="card-header d-flex align-items-center bg-hupa py-4">
                <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
                HUPA&nbsp; <?= __('Minify SCSS', 'hupa-minify') ?> </h5>
            <div class="card-body pb-4" style="min-height: 72vh">
                <div class="d-flex align-items-center">
                    <h5 class="card-title"><i
                                class="hupa-color fa fa-arrow-circle-right"></i> <?= __('Minify', 'hupa-minify') ?>
                        / <span id="currentSideTitle"><?= __('SCSS Compiler', 'hupa-minify') ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __('SCSS Compiler', 'hupa-minify') ?>"
                            data-type="start"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifySCSSStart"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-css3"></i>&nbsp;
                        <?= __('SCSS Settings', 'hupa-minify') ?>
                    </button>
                </div>
                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING MINIFY STARTSEITE -->
                    <div class="collapse show" id="collapseMinifySCSSStart"
                         data-bs-parent="#minify_display_data">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox"
                                   role="switch" name="compiler_aktiv"
                                   id="compilerAktiv" <?= !get_option('compiler_aktiv') ?: ' checked' ?>>
                            <label class="form-check-label" for="compilerAktiv">SCSS Compiler aktiv</label>
                        </div>
                        <fieldset id="scssConfig" <?=get_option('compiler_aktiv') ?: 'disabled'?>>
                            <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title d-flex">
                                        <i class="hupa-color d-block mt-1 icon-hupa-white"></i>&nbsp;<?= __('Minify SCSS Compiler Settings', 'hupa-minify') ?>
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

                                                <div class="form-floating">
                                                    <input name="source" type="text" class="form-control no-blur"
                                                           id="inputSourceFolder"
                                                           value="<?= get_option('minify_scss_source') ?>"
                                                           placeholder="<?= __('SCSS / SASS Location', 'hupa-minify'); ?>">
                                                    <label for="inputSourceFolder"><?= __('SCSS / SASS Location', 'hupa-minify'); ?></label>
                                                </div>

                                                <button data-target="#inputSourceFolder" type="button"
                                                        class="btn-show-folder-tree btn btn-blue-outline btn-sm my-3">
                                                    <i class="fa fa-folder-open-o"></i>
                                                    Location auswählen
                                                </button>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                <div class="form-floating">
                                                    <input name="destination" type="text" class="form-control no-blur"
                                                           id="inputDestinationFolder"
                                                           value="<?= get_option('minify_scss_destination') ?>"
                                                           placeholder="<?= __('CSS Location', 'hupa-minify') ?>">
                                                    <label for="inputDestinationFolder"><?= __('CSS Location', 'hupa-minify') ?></label>
                                                </div>

                                                <button data-target="#inputDestinationFolder" type="button"
                                                        class="btn-show-folder-tree btn btn-blue-outline my-3 btn-sm">
                                                    <i class="fa fa-folder-open-o"></i> Location auswählen
                                                </button>
                                            </div>
                                            <hr class="mb-2">
                                            <h6>Cache Settings</h6>
                                            <hr>
                                            <div class="mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" data-bs-toggle="collapse"
                                                           data-bs-target="#collapseCacheAktiv" name="cache_aktiv" type="checkbox"
                                                           role="switch"
                                                           id="SwitchCacheAktiv" <?= !get_option('minify_cache_aktiv') ?: 'checked' ?>>
                                                    <label class="form-check-label" for="SwitchCacheAktiv">Cache aktiv</label>
                                                </div>
                                                <div id="collapseCacheAktiv" class="collapse <?= !get_option('minify_cache_aktiv') ?: 'show' ?>">
                                                    <div class="col-12">
                                                        <div class="form-floating mt-3">
                                                            <input type="text" name="cache_path" class="form-control no-blur"
                                                                   value="<?=get_option('minify_cache_path')?>" id="inputCachePath" placeholder="Cache Pfad">
                                                            <label for="inputCachePath">Cache Pfad</label>
                                                        </div>
                                                        <div class="form-text mb-3">
                                                            Der angegebene Pfad muss existieren.
                                                        </div>

                                                        <button type="button" class="clear-cache btn btn-blue-outline mb-2 btn-sm">
                                                            <i class="bi bi-rocket-takeoff me-1"></i>
                                                            Cache leeren
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mb-2">
                                            <h6>Ausgabe Settings</h6>
                                            <hr>
                                            <div class="row">
                                                <div class="mb-3">
                                                    <div class="form-floating">
                                                        <select class="form-select no-blur" id="SelectMode"
                                                                name="formatter_mode" aria-label="Ausgabe">
                                                            <option value="expanded"
                                                                <?= get_option('minify_scss_formatter') == 'expanded' ? 'selected' : '' ?>>
                                                                expanded
                                                            </option>
                                                            <option value="compressed"
                                                                <?= get_option('minify_scss_formatter') == 'compressed' ? 'selected' : '' ?>>
                                                                compressed
                                                            </option>
                                                        </select>
                                                        <label for="SelectMode">Ausgabe</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="show-form-input">
                                            <div class="form-check form-switch form-check-inline me-3">
                                                <input class="form-check-input" data-bs-toggle="collapse"
                                                       data-bs-target="#collapseMapOption" name="map" type="checkbox"
                                                       role="switch"
                                                       id="SwitchSourceMap" <?= !get_option('scss_map_aktiv') ?: ' checked' ?>>
                                                <label class="form-check-label" for="SwitchSourceMap">Source Map
                                                    erstellen</label>
                                            </div>
                                            <div class="form-check form-check-inline form-switch me-3">
                                                <input class="form-check-input" name="enqueue_aktiv" type="checkbox"
                                                       role="switch"
                                                       id="SwitchEnqueueAktiv" <?=!get_option('minify_enqueue_aktiv') ?: 'checked'?>>
                                                <label class="form-check-label" for="SwitchEnqueueAktiv">Enqueue
                                                    Stylesheets erstellen <sup class="text-danger">(1)</sup></label>
                                            </div>
                                            <div class="form-check form-check-inline form-switch me-3">
                                                <input class="form-check-input" name="scss_login_aktiv" type="checkbox"
                                                       role="switch"
                                                       id="SwitchLoginAktiv" <?= !get_option('scss_login_aktiv') ?: ' checked' ?>>
                                                <label class="form-check-label" for="SwitchLoginAktiv">Compiler nur bei
                                                    Login aktiv <sup class="text-danger">*</sup></label>
                                            </div>
                                            <div class="form-text mt-2">
                                                <span class="text-danger">(1)</span>
                                                CSS-Stylesheets werden automatisch in den Header eingereiht.
                                            </div>
                                            <div class="form-text">
                                                <span class="text-danger">(2)</span>
                                                Wenn aktiviert, ist der SCSS-Compiler nur aktiv, wenn ein Benutzer
                                                angemeldet ist.
                                            </div>
                                        </div>
                                        <div class="show-form-input">
                                            <div class="collapse <?= get_option('scss_map_aktiv') ? ' show' : '' ?>"
                                                 id="collapseMapOption">
                                                <hr>
                                                <div class="mb-3">
                                                    <div class="form-floating">
                                                        <select class="form-select no-blur" id="SelectMapFile"
                                                                name="map_option" aria-label="Source Map Optionen">
                                                            <option value="map_file"
                                                                <?= get_option('minify_scss_map_option') == 'map_file' ? 'selected' : '' ?>>
                                                                File
                                                            </option>
                                                            <option value="map_inline"
                                                                <?= get_option('minify_scss_map_option') == 'map_inline' ? 'selected' : '' ?>>
                                                                Inline
                                                            </option>
                                                        </select>
                                                        <label for="SelectMapFile">Source Map Optionen</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mb-2">
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
                                        <button class="btn-show-folder-tree btn btn-light py-1 border btn-sm mb-2 mt-3 btn-sm">
                                            <i class="text-danger fa fa-close"></i>
                                            abbrechen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
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