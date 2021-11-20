<?php
defined( 'ABSPATH' ) or die();
/**
 * hupa-minify
 * @package Hummelt & Partner HUPA Minify
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */
//do_action('minify_plugin_set_defaults', 'set_defaults');
$sE = json_decode( get_option( 'minify_settings_entwicklung' ) );
$sP = json_decode( get_option( 'minify_settings_production' ) );

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
                            <form id="send-ajax-minify-settings" action="#" method="post">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title">
                                        <i class="hupa-color icon-hupa-white"></i>&nbsp;<?= __( 'Minify settings', 'hupa-minify' ) ?>
                                    </h5>
                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                </div>
                                <hr>
                                <div class="col-lg-12 pt-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="aktiv" type="checkbox"
                                               id="CheckMinActive"
											<?= ! get_option( 'minify_aktiv' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckMinActive">
											<?= __( 'active', 'hupa-minify' ) ?></label>
                                    </div>
                                </div>
                                <hr>
                                <fieldset class="minifyFieldset" <?= get_option( 'minify_aktiv' ) ? '' : 'disabled' ?>>
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Minify settings', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="d-lg-flex d-block">
                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-id="CheckCssGroupsActive" class="form-check-input groupActive"
                                                   name="css_aktiv" type="checkbox"
                                                   id="CheckCssActive"
												<?= ! get_option( 'minify_css_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckCssActive">
												<?= __( 'CSS active', 'hupa-minify' ) ?></label>
                                        </div>

                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-id="CheckJSGroupsActive" class="form-check-input groupActive"
                                                   name="js_aktiv" type="checkbox"
                                                   id="CheckJSActive"
												<?= ! get_option( 'minify_js_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckJSActive">
												<?= __( 'JS active', 'hupa-minify' ) ?></label>
                                        </div>

                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input class="form-check-input" name="html_aktiv" type="checkbox"
                                                   id="CheckHTMLActive"
                                                   aria-describedby="CheckVersionActiveHelp" <?= ! get_option( 'minify_html_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckHTMLActive">
												<?= __( 'HTML active', 'hupa-minify' ) ?></label>
                                        </div>
                                    </div>
                                    <hr>
                                    <b class="strong-font-weight"><?= __( 'Groups', 'hupa-minify' ) ?></b>
                                    <div class="d-lg-flex d-block pt-2">
                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input class="form-check-input" name="css_groups_aktiv" type="checkbox"
                                                   id="CheckCssGroupsActive"
												<?= get_option( 'minify_css_groups_aktiv' ) ? 'checked' : '' ?> <?= get_option( 'minify_css_aktiv' ) ? '' : ' disabled' ?>>
                                            <label class="form-check-label" for="CheckCssGroupsActive">
												<?= __( 'CSS Groups', 'hupa-minify' ) ?></label>
                                        </div>

                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-bs-toggle="collapse" data-bs-target="#jsGroupOptions"
                                                   class="form-check-input" name="js_groups_aktiv" type="checkbox"
                                                   id="CheckJSGroupsActive"
												<?= get_option( 'minify_js_groups_aktiv' ) ? 'checked ' : '' ?> <?= get_option( 'minify_js_aktiv' ) ? '' : ' disabled' ?>>
                                            <label class="form-check-label" for="CheckJSGroupsActive">
												<?= __( 'JS Groups', 'hupa-minify' ) ?></label>
                                        </div>
                                    </div>
                                    <div class="form-text pb-2">
										<?= __( 'If the group is active, all files are compressed and output in one file.', 'hupa-minify' ) ?>
                                    </div>
                                    <hr>
                                    <div class="collapse <?= get_option( 'minify_js_groups_aktiv' ) ? ' show' : '' ?>"
                                         id="jsGroupOptions">
                                        <div class="card card-body bg-light mb-2 shadow-sm">
                                            <div class="card-title strong-font-weight"><?= __( 'Javascript Group Options', 'hupa-minify' ) ?></div>
                                            <fieldset
                                                    class="wpCoreField" <?= get_option( 'minify_js_groups_aktiv' ) && get_option( 'minify_js_aktiv' ) ? '' : 'disabled' ?>>
                                                <div class="d-lg-flex d-block mt-2">
                                                    <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                        <input class="form-check-input wpJsCore" name="wp_core_aktiv"
                                                               type="checkbox"
                                                               id="CheckJQCoreGroupsActive"
															<?= ! get_option( 'minify_jquery_core_aktiv' ) ?: 'checked ' ?> >
                                                        <label class="form-check-label" for="CheckJQCoreGroupsActive">
															<?= __( 'WP jQuery Core', 'hupa-minify' ) ?></label>
                                                    </div>

                                                    <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                        <input class="form-check-input wpJsCore" name="wp_embed_aktiv"
                                                               type="checkbox"
                                                               id="CheckJSEmbedActive"
															<?= ! get_option( 'minify_wp_embed_aktiv' ) ?: 'checked ' ?>>
                                                        <label class="form-check-label" for="CheckJSEmbedActive">
															<?= __( 'WP Embed JS', 'hupa-minify' ) ?></label>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="form-text pt-1 pb-2">
												<?= __( 'If this option is active, "Minify" will include the WordPress Javascript files.', 'hupa-minify' ) ?>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input wpJsCore" name="css_import_aktiv"
                                               type="checkbox"
                                               id="CheckCssBubbleActive"
											<?= ! get_option( 'minify_css_bubble_import' ) ?: 'checked ' ?>>
                                        <label class="form-check-label" for="CheckCssBubbleActive">
											<?= __( 'CSS Imports', 'hupa-minify' ) ?></label>
                                    </div>
                                    <div class="form-text pt-1 mb-3 pb-2">
                                        Das Kombinieren mehrerer CSS-Dateien kann @import-Deklarationen nach Regeln
                                        platzieren, was ungültig ist.
                                        Minify versucht zu erkennen, wenn dies geschieht und setzt einen Warnkommentar
                                        an den Anfang der CSS-Ausgabe.
                                        Um dieses Problem zu lösen, können Sie entweder die @imports innerhalb Ihrer
                                        CSS-Dateien verschieben,
                                        oder diese Option aktivieren, die alle @imports an den Anfang der Ausgabe
                                        verschieben. Beachten Sie,
                                        dass das Verschieben von @imports CSS-Werte beeinflussen kann (deshalb ist diese
                                        Option standardmäßig deaktiviert).
                                    </div>
                                    <hr>
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input wpJsCore" name="static_aktiv"
                                               type="checkbox"
                                               id="CheckStaticActive"
											<?= ! get_option( 'minify_static_aktiv' ) ?: 'checked ' ?> disabled>
                                        <label class="form-check-label" for="CheckStaticActive">
											<?= __( 'static', 'hupa-minify' ) ?></label>
                                    </div>
                                    <div class="form-text pt-1 mb-3 pb-2">
                                        Statische Server-Funktion <i class="text-danger"> nicht verfügbar</i>.
                                    </div>

                                    <h5 class="card-title mx-n3 py-3 bg-hupa">
                                        <i class="fa fa-wrench ms-3"></i>&nbsp;<?= __( 'Minify Output', 'hupa-minify' ) ?>
                                    </h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">

                                            <label for="inputSelectCache"
                                                   class="form-label clickPathFolder"><?= __( 'Minify Cache', 'hupa-minify' ); ?></label>
                                            <select data-bs-target="#memCacheOptions" id="inputSelectCache"
                                                    name="cache_type" class="form-select mw-100">
                                                <option value="0" <?= get_option( 'minify_cache_type' ) == 0 ? ' selected' : '' ?>>
                                                    Default
                                                </option>
                                                <option value="1" <?= get_option( 'minify_cache_type' ) == 1 ? ' selected' : '' ?>>
                                                    APC
                                                </option>
                                                <option value="2" <?= get_option( 'minify_cache_type' ) == 2 ? ' selected' : '' ?>>
                                                    Memcache
                                                </option>
                                                <option value="3" <?= get_option( 'minify_cache_type' ) == 3 ? ' selected' : '' ?>>
                                                    Zend Platform
                                                </option>
                                                <option value="4" <?= get_option( 'minify_cache_type' ) == 4 ? ' selected' : '' ?>>
                                                    XCache
                                                </option>
                                                <option value="5" <?= get_option( 'minify_cache_type' ) == 5 ? ' selected' : '' ?>>
                                                    WinCache
                                                </option>
                                            </select>
                                            <div id="inputSubFolderHelp" class="form-text">
                                                <i class="text-danger fa fa-exclamation"></i>&nbsp; Ändern Sie diese
                                                Einstellung nur, wenn Sie genau wissen
                                                <b>welche Module</b> auf dem Server <b>Installiert</b> sind.
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                            <label for="inputSubFolder"
                                                   class="form-label clickPathFolder"><?= __( 'Subfolder of the installation', 'hupa-minify' ); ?></label>
                                            <input type="text" name="subfolder"
                                                   value="<?= get_option( 'minify_sub_folder' ) ?>"
                                                   class="form-control" id="inputSubFolder"
                                                   aria-describedby="inputSubFolderHelp" disabled>
                                            <div id="inputSubFolderHelp" class="form-text">
                                                Angabe ohne Slash bzw. Backslash am Anfang oder Ende.
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="collapse  <?= get_option( 'minify_cache_type' ) == 2 ? ' show' : '' ?>"
                                         id="memCacheOptions">
                                        <div class="card card-body bg-light mb-2 shadow-sm">
                                            <div class="card-title strong-font-weight"><?= __( 'Memcache Settings', 'hupa-minify' ) ?></div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                    <label for="inputMemHost"
                                                           class="form-label clickPathFolder"><?= __( 'Memcache Host', 'hupa-minify' ); ?></label>
                                                    <input type="text" name="memcache_host"
                                                           value="<?=get_option('minify_memcache_host')?>"
                                                           class="form-control" id="inputMemHost">
                                                </div>

                                                <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                    <label for="inputMemPort"
                                                           class="form-label clickPathFolder"><?= __( 'Memcache Port', 'hupa-minify' ); ?></label>
                                                    <input type="number" name="memcache_port"
                                                           value="<?=get_option('minify_memcache_port')?>"
                                                           class="form-control" id="inputMemPort">
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                    </div>

                                    <b class="strong-font-weight d-block pt-3 pb-2"><?= __( 'active settings', 'hupa-minify' ) ?></b>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                               name="active_settings" id="settingsRadio1"
                                               value="1" <?= get_option( 'minify_settings_select' ) == '1' ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                               for="settingsRadio1"><?= __( 'Development', 'hupa-minify' ) ?></label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                               name="active_settings" id="settingsRadio2"
                                               value="2" <?= get_option( 'minify_settings_select' ) == '2' ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                               for="settingsRadio2"><?= __( 'Production', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>

                                    <div class="pb-3">
                                        <div class="row align-items-stretch  gx-3">
                                            <div class="col-xl-6 col-lg-12 flex-fill align-self-stretch py-1">
                                                <div class=" h-100">
                                                    <div class="p-3 bg-light border shadow-sm h-100">
                                                        <b class="strong-font-weight"><?= __( 'Settings Development', 'hupa-minify' ) ?></b>
                                                        <hr>
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input wpJsCore"
                                                                   name="develop_debug"
                                                                   type="checkbox"
                                                                   id="CheckDevDebugActive"
																<?= ! $sE->debug_aktiv ?: 'checked ' ?>>
                                                            <label class="form-check-label" for="CheckDevDebugActive">
																<?= __( 'Debug mode', 'hupa-minify' ) ?>
                                                            </label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Debug mode', 'hupa-minify' ) ?>"
                                                                  data-bs-content="Im Debug-Modus komprimiert Minify die Dateien nicht, sondern
                                                                                sendet kombinierte Dateien mit vorangestellten
                                                                                Kommentaren zu jeder Zeile, um die Zeilennummer in der
                                                                                 ursprünglichen Quelldatei anzuzeigen.">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>
                                                        </div>

                                                        <div class="form-check form-switch mb-3 ">
                                                            <input class="form-check-input"
                                                                   name="develop_verkettung_aktiv"
                                                                   type="checkbox"
                                                                   id="CheckDevVerkettungActive"
																<?= ! $sE->verkettung ?: 'checked ' ?>>
                                                            <label class="form-check-label"
                                                                   for="CheckDevVerkettungActive">
																<?= __( 'Concatenate', 'hupa-minify' ) ?></label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Verkettung', 'hupa-minify' ) ?>"
                                                                  data-bs-content="Verketten Sie die Dateien, aber minimieren Sie sie nicht. Dies kann zu Testzwecken verwendet werden.">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>

                                                        </div>

                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input wpJsCore"
                                                                   name="develop_cache_aktiv"
                                                                   type="checkbox"
                                                                   id="CheckDevCacheActive"
																<?= ! $sE->cache_aktiv ?: 'checked ' ?>>
                                                            <label class="form-check-label" for="CheckDevCacheActive">
																<?= __( 'Cache active', 'hupa-minify' ) ?></label>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <label for="inputDevCacheFolder"
                                                                   class="form-label"><?= __( 'Cache Path', 'hupa-minify' ); ?></label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover"
                                                                  data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Cache Path', 'hupa-minify' ); ?>"
                                                                  data-bs-content="Angabe absoluter oder relativer Pfad. (z.B. /tmp oder C:\\WINDOWS\\Temp)">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>
                                                            <input type="text" name="develop_cache_path"
                                                                   value="<?= $sE->min_cachePath ?>"
                                                                   class="form-control" id="inputDevCacheFolder">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="inputDevCacheAge"
                                                                   class="form-label"><?= __( 'Cache max age', 'hupa-minify' ); ?></label>
                                                            <input type="number" name="develop_cache_time"
                                                                   value="<?= $sE->cache_max_age ?>"
                                                                   class="form-control" id="inputDevCacheAge">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lig-12 flex-fill py-1 align-self-stretch">
                                                <div class=" h-100">
                                                    <div class="p-3 bg-light border shadow-sm h-100">
                                                        <b class="strong-font-weight"><?= __( 'Production settings', 'hupa-minify' ) ?></b>
                                                        <hr>
                                                        <div class="form-check form-switch mb-3">
                                                            <input class="form-check-input wpJsCore"
                                                                   name="product_debug"
                                                                   type="checkbox"
                                                                   id="CheckProDebugActive"
																<?= ! $sP->debug_aktiv ?: 'checked ' ?>>
                                                            <label class="form-check-label" for="CheckProDebugActive">
																<?= __( 'Debug mode', 'hupa-minify' ) ?>
                                                            </label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Debug mode', 'hupa-minify' ) ?>"
                                                                  data-bs-content="Im Debug-Modus komprimiert Minify die Dateien nicht, sondern
                                                                                sendet kombinierte Dateien mit vorangestellten
                                                                                Kommentaren zu jeder Zeile, um die Zeilennummer in der
                                                                                 ursprünglichen Quelldatei anzuzeigen.">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>
                                                        </div>

                                                        <div class="form-check form-switch mb-3 ">
                                                            <input class="form-check-input"
                                                                   name="product_verkettung_aktiv"
                                                                   type="checkbox"
                                                                   id="CheckProVerkettungActive"
																<?= ! $sP->verkettung ?: 'checked ' ?>>
                                                            <label class="form-check-label"
                                                                   for="CheckProVerkettungActive">
																<?= __( 'Verkettung', 'hupa-minify' ) ?></label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Verkettung', 'hupa-minify' ) ?>"
                                                                  data-bs-content="Verketten Sie die Dateien, aber minimieren Sie sie nicht. Dies kann zu Testzwecken verwendet werden.">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>
                                                        </div>

                                                        <div class="form-check form-switch mb-3 ">
                                                            <input class="form-check-input wpJsCore"
                                                                   name="produktion_cache_aktiv"
                                                                   type="checkbox"
                                                                   id="CheckProCacheActive"
																<?= ! $sP->cache_aktiv ?: 'checked ' ?>>
                                                            <label class="form-check-label" for="CheckProCacheActive">
																<?= __( 'Cache active', 'hupa-minify' ) ?></label>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <label for="inputDevCacheFolder"
                                                                   class="form-label"><?= __( 'Cache Path', 'hupa-minify' ); ?></label>
                                                            <span class="cursor-help d-inline-block"
                                                                  data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                                  title="<?= __( 'Cache Path', 'hupa-minify' ); ?>"
                                                                  data-bs-content="Angabe absoluter oder relativer Pfad. (z.B. /tmp oder C:\\WINDOWS\\Temp)">
                                                        <i class="font-blue fa fa-info-circle"></i>
                                                        </span>
                                                            <input type="text" name="product_cache_path"
                                                                   value="<?= $sP->min_cachePath ?>"
                                                                   class="form-control" id="inputDevCacheFolder">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="inputProCacheAge"
                                                                   class="form-label"><?= __( 'Cache max age', 'hupa-minify' ); ?></label>
                                                            <input type="number" name="product_cache_time"
                                                                   value="<?= $sP->cache_max_age ?>"
                                                                   class="form-control" id="inputProCacheAge">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <h5 class="card-title mx-n3 py-3 bg-hupa">
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
												<?= __( 'remove', 'hupa-minify' ) ?></label>
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
                                            <input class="form-check-input" name="css_gutenberg_aktiv" type="checkbox"
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
                                </fieldset>
                            </form>
                            <div class="ajax-status-spinner ms-auto d-block mb-2 pe-2">&nbsp;</div>
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