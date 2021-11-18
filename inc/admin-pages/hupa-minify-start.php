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
                                <i class="font-blue fa fa-wordpress"></i>&nbsp;<?= __( 'Inbox', 'hupa-minify' ) ?>
                            </h5>
                            <hr>
                            <?php
                        $file = HUPA_MINIFY_ROOT_PATH . '/wp-content/plugins/wp-post-selector/inc/assets/js/tools/lightbox/blueimp-gallery.min.js';
                        $today = mktime (0,0,0, date("m"), date("d"), date("Y"));
                        echo $today.'<br>';
                        echo filemtime($file)."<br>";
                     //   if($today >  filemtime($file))
                    /*     echo "Ist älter<br>";
                        else
                        echo "Von heute<br>";
                        echo HUPA_MINIFY_OPCACHE."<br>";
                        echo HUPA_MINIFY_MEMCACHE."<br>";*/

                     ?>
                        </div>
                    </div><!--Startseite-->

                    <!--//TODO JOB WARNING SITE TWO-->
                    <div class="collapse" id="collapseMinifyTwo"
                         data-bs-parent="#minify_display_data">
                        <div class="border rounded mt-1 mb-3 shadow-sm p-3 bg-custom-gray"  style="min-height: 53vh">
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