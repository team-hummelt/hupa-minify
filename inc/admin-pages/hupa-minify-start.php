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
global $hupa_server_class;

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
                        / <span id="currentSideTitle"><?= __( 'Settings', 'hupa-minify' ) ?></span>
                    </h5>
                </div>
                <hr>
                <div class="settings-btn-group d-block d-md-flex flex-wrap">
                    <button data-site="<?= __( 'Settings', 'hupa-minify' ) ?>"
                            data-type="start"
                            type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyStartSite"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm active" disabled>
                        <i class="fa fa-wrench"></i>&nbsp;
						<?= __( 'Settings', 'hupa-minify' ) ?>
                    </button>

                    <button data-site="<?= __( 'Ausgabe', 'hupa-minify' ) ?>"
                            data-type="formular"
                            type="button" id="formEditCollapseBtn"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyTwo"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-server"></i>&nbsp;
						<?= __( 'Ausgabe', 'hupa-minify' ) ?>
                    </button>

                    <button data-site="<?= __( 'WordPress', 'hupa-minify' ) ?>"
                            data-type="formular"
                            type="button" id="formEditCollapseBtn"
                            data-bs-toggle="collapse" data-bs-target="#collapseMinifyThree"
                            class="btn-formular-collapse btn btn-hupa btn-outline-secondary btn-sm"><i
                                class="fa fa-wordpress"></i>&nbsp;
						<?= __( 'WordPress', 'hupa-minify' ) ?>
                    </button>
                    <div class="ms-auto">

                    <button data-method="change_ip_api_aktiv"  class="btnResetBtnMinify d-none btn btn-danger btn-sm">
                        <i class="fa fa-tag"></i>
                        <span id="changeIpApi"> <?=get_option('ip_api_aktiv') ? 'IP-API deaktivieren' : 'IP-API aktivieren'?></span>
                    </button>
                    <button data-method="reset_minify_settings" class="btnResetBtnMinify d-none btn btn-danger btn-sm"><i class="fa fa-random"></i> Reset</button>
                    </div>
                </div>

                <hr>
                <div id="minify_display_data">
                    <!--  TODO JOB WARNING MINIFY STARTSEITE -->
                    <div class="collapse show" id="collapseMinifyStartSite"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <form class="send-ajax-minify-settings" action="#" method="post">
                                <input type="hidden" name="method" value="update_minify_settings">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title d-flex">
                                        <i class="btn-reset-double hupa-color d-block mt-1 icon-hupa-white"></i>&nbsp;<?= __( 'Minify settings', 'hupa-minify' ) ?>
                                    </h5>
                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                </div>
                                <hr>
                                <div class="card card-body bg-light mb-2 shadow-sm">
                                    <div class="d-lg-flex d-block">

                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-bs-toggle="collapse" data-bs-target="#collapseCSSSettings"
                                                   class="form-check-input"
                                                   name="css_aktiv" type="checkbox"
                                                   id="CheckCssActive"
												<?= ! get_option( 'minify_css_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckCssActive">
												<?= __( 'CSS active', 'hupa-minify' ) ?></label>
                                        </div>
                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-bs-toggle="collapse" data-bs-target="#collapseJSSettings"
                                                   class="form-check-input"
                                                   name="js_aktiv" type="checkbox"
                                                   id="CheckJSActive"
												<?= ! get_option( 'minify_js_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckJSActive">
												<?= __( 'JS active', 'hupa-minify' ) ?></label>
                                        </div>

                                        <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                            <input data-bs-toggle="collapse" data-bs-target="#collapseHTMLSettings"
                                                   class="form-check-input" name="html_aktiv" type="checkbox"
                                                   id="CheckHTMLActive"
                                                   aria-describedby="CheckVersionActiveHelp" <?= ! get_option( 'minify_html_aktiv' ) ?: 'checked' ?>>
                                            <label class="form-check-label" for="CheckHTMLActive">
												<?= __( 'HTML active', 'hupa-minify' ) ?></label>
                                        </div>
                                    </div>
                                </div>

                                <!--//WARNING CSS Settings-->
                                <div class="collapse <?= ! get_option( 'minify_css_aktiv' ) ?: ' show' ?>"
                                     id="collapseCSSSettings">
                                    <div class="card card-body bg-light mb-2 shadow-sm">
                                        <div class="card-title strong-font-weight">
                                            <i class="fa fa-gears"></i>
											<?= __( 'CSS Settings', 'hupa-minify' ) ?></div>
                                        <hr>
                                        <div class="d-lg-flex d-block mt-2">
                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input" name="css_groups_aktiv" type="checkbox"
                                                       id="CheckCssGroupsActive"
													<?= get_option( 'minify_css_groups_aktiv' ) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="CheckCssGroupsActive">
													<?= __( 'CSS Groups', 'hupa-minify' ) ?>
                                                    <sup class="text-danger strong-font-weight">1</sup>
                                                </label>
                                            </div>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input wpJsCore" name="css_import_aktiv"
                                                       type="checkbox"
                                                       id="CheckCssBubbleActive"
													<?= ! get_option( 'minify_css_bubble_import' ) ?: 'checked ' ?>>
                                                <label class="form-check-label" for="CheckCssBubbleActive">
													<?= __( 'CSS Imports', 'hupa-minify' ) ?>
                                                    <sup class="text-danger strong-font-weight">2</sup></label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-text pb-2 pt-1">
                                            <b class="text-danger strong-font-weight">( 1 )</b>
											<?= __( 'If the group is active, all files are compressed and output in one file.', 'hupa-minify' ) ?>
                                        </div>
                                        <div class="form-text pb-2">
                                            <b class="text-danger strong-font-weight">( 2 )</b>
                                            Das Kombinieren mehrerer CSS-Dateien kann @import-Deklarationen nach Regeln
                                            platzieren, was ungültig ist.
                                            Minify versucht zu erkennen, wenn dies geschieht und setzt einen
                                            Warnkommentar
                                            an den Anfang der CSS-Ausgabe.
                                            Um dieses Problem zu lösen, können Sie entweder die @imports innerhalb Ihrer
                                            CSS-Dateien verschieben,
                                            oder diese Option aktivieren, die alle @imports an den Anfang der Ausgabe
                                            verschieben. Beachten Sie,
                                            dass das Verschieben von @imports CSS-Werte beeinflussen kann (deshalb ist
                                            diese
                                            Option standardmäßig deaktiviert).
                                        </div>
                                    </div>
                                </div> <!--CSS Ende-->

                                <!--//WARNING JS Settings-->
                                <div class="collapse <?= ! get_option( 'minify_js_aktiv' ) ?: ' show' ?>"
                                     id="collapseJSSettings">
                                    <div class="card card-body bg-light mb-2 shadow-sm">
                                        <div class="card-title strong-font-weight">
                                            <i class="fa fa-gears"></i>
											<?= __( 'Javascript Settings', 'hupa-minify' ) ?></div>
                                        <hr>
                                        <div class="d-lg-flex d-block mt-2">
                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input data-bs-toggle="collapse" data-bs-target="#jsGroupOptions"
                                                       class="form-check-input" name="js_groups_aktiv" type="checkbox"
                                                       id="CheckJSGroupsActive"
													<?= get_option( 'minify_js_groups_aktiv' ) ? 'checked ' : '' ?>>
                                                <label class="form-check-label" for="CheckJSGroupsActive">
													<?= __( 'JS Groups', 'hupa-minify' ) ?> <sup
                                                            class="text-danger strong-font-weight">1</sup></label>
                                            </div>

                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input wpJsCore" name="wp_core_aktiv"
                                                       type="checkbox"
                                                       id="CheckJQCoreActive"
													<?= ! get_option( 'minify_jquery_core_aktiv' ) ?: 'checked ' ?>
                                                       disabled>
                                                <label class="form-check-label" for="CheckJQCoreActive">
													<?= __( 'WP jQuery Core', 'hupa-minify' ) ?> <sup
                                                            class="text-danger strong-font-weight">2</sup></label>
                                            </div>

                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input wpJsCore" name="wp_embed_aktiv"
                                                       type="checkbox"
                                                       id="CheckWPEmbedActive"
			                                        <?= ! get_option( 'minify_wp_embed_aktiv' ) ?: 'checked ' ?>>
                                                <label class="form-check-label" for="CheckWPEmbedActive">
			                                        <?= __( 'WP Embed Dateien', 'hupa-minify' ) ?> <sup
                                                            class="text-danger strong-font-weight">3</sup></label>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="form-text  pt-1">
                                            <b class="text-danger strong-font-weight">( 1 )</b>
											<?= __( 'If the group is active, all files are compressed and output in one file.', 'hupa-minify' ) ?>
                                        </div>
                                        <div class="form-text pt-1">
                                            <b class="text-danger strong-font-weight">( 2 )</b>
											<?= __( 'If this option is active, "Minify" will include the WordPress Javascript files.', 'hupa-minify' ) ?>
                                        </div>
                                        <div class="form-text pt-1 pb-2">
                                            <b class="text-danger strong-font-weight">( 3 )</b>
		                                    Ist diese Option aktiv, wird versucht die automatisch generierten WordPress JS-Dateien mit einzubeziehen.
                                        </div>
                                    </div>

                                </div><!--JS Ende-->

                                <!--//WARNING HTML Settings-->
                                <div class="collapse <?= ! get_option( 'minify_html_aktiv' ) ?: ' show' ?>"
                                     id="collapseHTMLSettings">
                                    <div class="card card-body bg-light mb-2 shadow-sm">
                                        <div class="card-title strong-font-weight">
                                            <i class="fa fa-gears"></i>
											<?= __( 'HTML Settings', 'hupa-minify' ) ?></div>
                                        <hr>
                                        <div class="d-lg-flex d-block mt-2">
                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input" name="html_inline_css" type="checkbox"
                                                       id="CheckCompressCSSActive"
													<?= get_option( 'minify_html_inline_css' ) ? 'checked ' : '' ?>>
                                                <label class="form-check-label" for="CheckCompressCSSActive">
													<?= __( 'Inline CSS Komprimieren', 'hupa-minify' ) ?> </label>
                                            </div>

                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input wpJsCore" name="html_inline_js"
                                                       type="checkbox"
                                                       id="CheckCompressJSActive"
													<?= ! get_option( 'minify_html_inline_js' ) ?: 'checked ' ?> >
                                                <label class="form-check-label" for="CheckCompressJSActive">
													<?= __( 'Inline JS Komprimieren', 'hupa-minify' ) ?> </label>
                                            </div>

                                            <div class="form-check form-switch mb-3 mb-lg-0 me-3">
                                                <input class="form-check-input wpJsCore" name="html_comment"
                                                       type="checkbox"
                                                       id="CheckCommentsActive"
													<?= ! get_option( 'minify_html_comments' ) ?: 'checked ' ?> >
                                                <label class="form-check-label" for="CheckCommentsActive">
													<?= __( 'HTML Kommentare entfernen', 'hupa-minify' ) ?> </label>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div> <!--HTML Ende-->
                                <hr>
                                <button data-bs-toggle="collapse" data-bs-target="#collapseServerSettings"
                                        class="btn btn-outline-secondary btn-hupa btn-sm mb-3" type="button">
                                    <i class="fa fa-server"></i>&nbsp; Server Settings
                                </button>
                                <!--//WARNING Server Settings-->
                                <div class="collapse"
                                     id="collapseServerSettings">
                                    <div class="card card-body bg-light mb-3 shadow-sm">
                                        <div class="card-title strong-font-weight">
                                            <i class="fa fa-gears"></i>
											<?= __( 'Server Settings', 'hupa-minify' ) ?></div>
                                        <hr>
                                        <div class="form-check form-switch mb-1">
                                            <input class="form-check-input wpJsCore" name="static_aktiv"
                                                   type="checkbox"
                                                   id="CheckStaticActive"
												<?= ! get_option( 'minify_static_aktiv' ) ?: 'checked ' ?> disabled>
                                            <label class="form-check-label" for="CheckStaticActive">
												<?= __( 'Statische Server-Funktion', 'hupa-minify' ) ?> <sup
                                                        class="text-danger strong-font-weight">1</sup> </label>
                                        </div>
                                        <div class="form-text">
                                            <i class="text-danger fa fa-exclamation"></i>
                                            <i>Statische Server-Funktion <span
                                                        class="text-danger"> nicht verfügbar!</span></i>.
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                <label for="inputSelectCache"
                                                       class="form-label clickPathFolder"><?= __( 'Minify Cache', 'hupa-minify' ); ?></label>
                                                <select data-bs-target="#memCacheOptions" id="inputSelectCache"
                                                        name="cache_type" class="form-select mw-100">
                                                    <option value="1" <?= get_option( 'minify_cache_type' ) == 1 ? ' selected' : '' ?>>
                                                        Default
                                                    </option>
                                                    <option value="2" <?= get_option( 'minify_cache_type' ) == 2 ? ' selected' : '' ?>>
                                                        APC
                                                    </option>
                                                    <option value="3" <?= get_option( 'minify_cache_type' ) == 3 ? ' selected' : '' ?>>
                                                        Memcache
                                                    </option>
                                                    <option value="4" <?= get_option( 'minify_cache_type' ) == 4 ? ' selected' : '' ?>>
                                                        Zend Platform
                                                    </option>
                                                    <option value="5" <?= get_option( 'minify_cache_type' ) == 5 ? ' selected' : '' ?>>
                                                        XCache
                                                    </option>
                                                    <option value="6" <?= get_option( 'minify_cache_type' ) == 6 ? ' selected' : '' ?>>
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

                                        <div class="form-text mb-1">
                                            <b class="strong-font-weight text-danger">( 1 )</b>
                                            Die Statische Server-Funktion ermöglicht die Bereitstellung von Dateien
                                            direkt aus dem Dateisystem <small>( <i>ohne Overhead</i> )</small> für eine
                                            viel bessere Leistung.
                                        </div>
                                        <hr>
                                        <div class="collapse  <?= get_option( 'minify_cache_type' ) == 3 ? ' show' : '' ?>"
                                             id="memCacheOptions">
											<?php

                                            //minify_check_memory_limit_cal
											if ( $hupa_server_class->minify_check_memory_limit_cal() ) {
												$memStat = '<span style="color:#79b500"> Memcache installiert</span>';
											} else {
												$memStat = '<span class="text-danger"> Memcache nicht installiert!</span>';
											}
											?>
                                            <div class="card card-body bg-light mb-2 shadow-sm">
                                                <div class="card-title strong-font-weight"><?= __( 'Memcache Settings', 'hupa-minify' ) ?>
                                                    <small class="fw-normal small d-block"> <?= $memStat ?></small>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                        <label for="inputMemHost"
                                                               class="form-label clickPathFolder"><?= __( 'Memcache Host', 'hupa-minify' ); ?></label>
                                                        <input type="text" name="memcache_host"
                                                               value="<?= get_option( 'minify_memcache_host' ) ?>"
                                                               class="form-control" id="inputMemHost">
                                                    </div>

                                                    <div class="col-xl-6 col-lg-6 col-12 pe-2 mb-3">
                                                        <label for="inputMemPort"
                                                               class="form-label clickPathFolder"><?= __( 'Memcache Port', 'hupa-minify' ); ?></label>
                                                        <input type="number" name="memcache_port"
                                                               value="<?= get_option( 'minify_memcache_port' ) ?>"
                                                               class="form-control" id="inputMemPort">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--Server Settings Ende-->
                            </form>
                            <div class="ajax-status-spinner ms-auto d-block mb-2 pe-2">&nbsp;</div>
                        </div>
                    </div><!--Startseite-->

                    <!--//TODO JOB WARNING SITE TWO-->
                    <div class="collapse" id="collapseMinifyTwo"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <form class="send-ajax-minify-settings" action="#" method="post">
                                <input type="hidden" name="method" value="minify_ausgabe_settings">
                                <div class="d-flex align-items-center">
                                    <div class="card-title strong-font-weight">
                                        <i class="fa fa-gears"></i>
										<?= __( 'Ausgabe Settings', 'hupa-minify' ) ?></div>
                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                </div>
                                <hr>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           name="active_settings" id="settingsRadio1"
                                           value="1" <?= get_option( 'minify_settings_select' ) == '1' ? 'checked' : '' ?>>
                                    <label class="form-check-label"
                                           for="settingsRadio1"><?= __( 'Development', 'hupa-minify' ) ?> aktiv</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                           name="active_settings" id="settingsRadio2"
                                           value="2" <?= get_option( 'minify_settings_select' ) == '2' ? 'checked' : '' ?>>
                                    <label class="form-check-label"
                                           for="settingsRadio2"><?= __( 'Production', 'hupa-minify' ) ?> aktiv</label>
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
                                                        <sup class="text-danger strong-font-weight"> 1</sup>
                                                    </div>
                                                    <div class="form-check form-switch mb-3 ">
                                                        <input class="form-check-input"
                                                               name="develop_verkettung_aktiv"
                                                               type="checkbox"
                                                               id="CheckDevVerkettungActive"
															<?= ! $sE->verkettung ?: 'checked ' ?>>
                                                        <label class="form-check-label"
                                                               for="CheckDevVerkettungActive">
															<?= __( 'Verkettung', 'hupa-minify' ) ?></label>
                                                        <sup class="text-danger strong-font-weight"> 2</sup>
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
                                                        <sup class="text-danger strong-font-weight"> 3</sup>
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
                                                        <sup class="text-danger strong-font-weight"> 1</sup>
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
                                                        <sup class="text-danger strong-font-weight"> 2</sup>
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
                                                        <sup class="text-danger strong-font-weight"> 3</sup>
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
                            </form>
                            <hr>

                            <div class="form-text mb-1">
                                <b class="strong-font-weight text-danger">( 1 )</b>
                                Im Debug-Modus komprimiert Minify die Dateien nicht, sondern
                                sendet kombinierte Dateien mit vorangestellten
                                Kommentaren zu jeder Zeile, um die Zeilennummer in der
                                ursprünglichen Quelldatei anzuzeigen.
                            </div>
                            <div class="form-text mb-1">
                                <b class="strong-font-weight text-danger">( 2 )</b>
                                Verketten Sie die Dateien, aber minimieren Sie sie nicht. Dies kann zu Testzwecken
                                verwendet werden.
                            </div>
                            <div class="form-text mb-1">
                                <b class="strong-font-weight text-danger">( 3 )</b>
                                Angabe absoluter oder relativer Pfad. (z.B. /tmp oder C:\\WINDOWS\\Temp)
                            </div>
                            <hr>
                        </div>
                    </div>

                    <!--//TODO JOB WARNING SITE DREI-->
                    <div class="collapse" id="collapseMinifyThree"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray" style="min-height: 53vh">
                            <form class="send-ajax-minify-settings" action="#" method="post">
                                <input type="hidden" name="method" value="minify_wordpress_settings">


                                <div class="d-flex align-items-center">
                                    <h5 class="card-title">
                                        <i class="hupa-color icon-hupa-white"></i>&nbsp;<?= __( 'Minify WordPress', 'hupa-minify' ) ?>
                                    </h5>
                                    <div class="ajax-status-spinner ms-auto d-inline-block mb-2 pe-2"></div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Discovery-Service-Links (RSD) entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="rsd_aktiv" type="checkbox"
                                               id="CheckRSDLinkActive"
                                               aria-describedby="CheckRSDLinkActiveHelp" <?= (int) ! get_option( 'minify_rsd_aktiv' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckRSDLinkActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckRSDLinkActiveHelp" class="form-text">
                                        Die Funktion <b>rsd_link</b> fügt dem Endpunkt des Really Simple Discovery
                                        Service einen Link hinzu.
                                        Dieser Link ermöglicht Drittanbieter-Tools, Ihren Blog zu entdecken und
                                        möglicherweise Pingback zu Ihren Posts zu verwenden.
                                        Der Link bietet auch Tools von Drittanbietern einen Endpunkt, um Beiträge auf
                                        Ihrer Website zu veröffentlichen – natürlich mit
                                        Ihrer ausdrücklichen Erlaubnis! Entfernen Sie diesen Link, wenn Sie keine
                                        Pingbacks zulassen möchten und nicht möchten,
                                        dass Ihr Veröffentlichungslink von Drittanbietern mit diesem Code entdeckt wird.
                                    </div>
                                </div>
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
                                    <hr>
                                    <div id="CheckVersionActiveHelp" class="form-text">
                                        Die Funktion <b>wp_generator</b> fügt dem Header ein Meta-Tag mit der
                                        WordPress-Versionsnummer hinzu.
                                        Die Logik hinter dem Entfernen dieser Versionsnummer besteht darin, dass sie
                                        eine gewisse <b>Sicherheit</b> bietet,
                                        indem sie die <b>Version versteckt</b> – da Hacker angeblich spezielle Hacks für
                                        bestimmte WordPress-Versionen haben!
                                        Obwohl erfahrene Hacker immer noch einen Weg finden können, in Ihre Website
                                        einzudringen, stellt dies dennoch
                                        einige Schwierigkeiten für Hacker dar.
                                    </div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'RSS-Feed-Links entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="rss_aktiv" type="checkbox"
                                               id="CheckRssLinkActive"
                                               aria-describedby="CheckRssLinkActiveHelp" <?= (int) ! get_option( 'minify_rss_link' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckRssLinkActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckRssLinkActiveHelp" class="form-text">
                                        <b>feed_links</b> generiert Links zu RSS-Feeds von Posts/Seiten. Sofern Ihre
                                        WordPress-Site keine Post-/Seiten-Feeds an
                                        Tools/Apps/Sites von Drittanbietern verteilt, möchten Sie diese Links
                                        möglicherweise aus der Kopfzeile entfernen.
                                    </div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'zusätzliche Feed-Links entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="rss_extra" type="checkbox"
                                               id="CheckRssExtraLinkActive"
                                               aria-describedby="CheckRssExtraLinkActiveHelp" <?= (int) ! get_option( 'minify_rss_extra' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckRssExtraLinkActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckRssExtraLinkActiveHelp" class="form-text">
                                        <b>feed_links_extra</b> generiert Links zu RSS-Feeds für Kategorien, Kommentare
                                        und Tags usw.
                                        Einige sehr beliebte WordPress-basierte Nachrichten- und Zeitschriftenportale
                                        können diese Links verwenden,
                                        aber für die meisten anderen WordPress-Sites sind diese sinnlos.
                                    </div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Live Writer-Unterstützung entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="live_writer" type="checkbox"
                                               id="CheckLiveWriterActive"
                                               aria-describedby="CheckLiveWriterActiveHelp" <?= (int) ! get_option( 'minify_live_writer' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckLiveWriterActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckLiveWriterActiveHelp" class="form-text">
                                        <b>wlwmanifest_link</b> fügt Unterstützung für Windows Live Writer hinzu. Das
                                        heißt – Sie können Windows Live Writer verwenden,
                                        um auf Ihrer WordPress-Site zu schreiben und zu veröffentlichen.
                                    </div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Relationale Links entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="posts_rel" type="checkbox"
                                               id="CheckPostRelActive"
                                               aria-describedby="CheckPostRelActiveHelp" <?= (int) ! get_option( 'minify_posts_rel' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckPostRelActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckPostRelActiveHelp" class="form-text">
                                        <b>adjacent_posts_rel_link</b> – zeigt die relationalen Links für die Beiträge
                                        an, die neben dem aktuellen Beitrag liegen.
                                        Ich habe noch keinen praktischen Nutzen dieser relationalen Links gefunden. Es
                                        ist also wahrscheinlich in Ordnung, sie zu entfernen,
                                        es sei denn, Sie haben eine bestimmte Verwendung für diese Art von Links.
                                    </div>
                                </div>
                                <hr>

                                <div class="col-lg-12 pt-2">
                                    <h6>
                                        <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Shortlink-Tag entfernen', 'hupa-minify' ); ?>
                                    </h6>
                                    <hr>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" name="short_link" type="checkbox"
                                               id="CheckShortLinkActive"
                                               aria-describedby="CheckShortLinkActiveHelp" <?= (int) ! get_option( 'minify_shortlink_aktiv' ) ?: 'checked' ?>>
                                        <label class="form-check-label" for="CheckShortLinkActive">
											<?= __( 'remove', 'hupa-minify' ) ?></label>
                                    </div>
                                    <hr>
                                    <div id="CheckShortLinkActiveHelp" class="form-text">
                                        <b>wp_shortlink</b> – diese Funktion fügt einen Shortlink für die aktuelle Seite
                                        (falls vorhanden) im Header
                                        mit rel=shortlink hinzu. Während Shortlinks leicht zu kopieren, einzufügen und
                                        zu verwenden sein können,
                                        sehe ich keine andere Verwendung für sie. Außerdem ist es aus Suchmaschinensicht
                                        keine gute Idee, 2 URLs für
                                        einen Beitrag/eine Seite zu haben.
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
                                <div class="ajax-status-spinner ms-auto d-block mb-2 pe-2">&nbsp;</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!--card-->
            <small class="card-body-bottom" style="right: 1.5rem">Minify Version: <i
                        class="hupa-color">v<?= HUPA_MINIFY_PLUGIN_VERSION ?></i></small>
        </div>

    </div>
</div>

<div id="snackbar-success"></div>
<div id="snackbar-warning"></div>