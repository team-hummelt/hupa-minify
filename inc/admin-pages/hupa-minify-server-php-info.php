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
    <div class="wrap wpss_info bg-light">
        <h5 class="card-header d-flex align-items-center bg-hupa py-4">
            <i class="icon-hupa-white d-block mt-2" style="font-size: 2rem"></i>&nbsp;
			<?= __( 'PHP Information', 'hupa-minify' ) ?> </h5>
        <div class="d-flex align-items-center mb-4">
            <h5 class="card-title bg-light w-100 p-3 shadow" style="height: 5rem"><i
                        class="hupa-color fa fa-arrow-circle-right"></i>
                <span id="currentSideTitle"><?= __( 'Server Stats', 'hupa-minify' ) ?>&nbsp;- <?= __( 'PHP Information', 'hupa-minify' ) ?></span>
                <small class="small fw-normal mt-2 d-block"><?= __( 'This page will show you the in-depth information about the PHP installation on your server.', 'hupa-minify' ) ?></small>
            </h5>
        </div>
		<?php if ( ! class_exists( 'DOMDocument' ) ) {
			echo '<p>Die <a href="https://php.net/manual/en/class.domdocument.php" target="_blank">DOMDocument</a> Erweiterung muss aktiviert sein.</p>';
		} else {
			ob_start();
			phpinfo();
			$phpinfo = ob_get_contents();
			ob_end_clean();

			// Use DOMDocument to parse phpinfo()
			libxml_use_internal_errors( true );
			$html = new DOMDocument( '1.0', 'UTF-8' );
			$html->loadHTML( $phpinfo );

			// Style process
			$tables = $html->getElementsByTagName( 'table' );
			foreach ( $tables as $table ) {
				$table->setAttribute( 'class', 'widefat table table-bordered table-striped' );
			}

			// We only need the <body>
			$xpath = new DOMXPath( $html );
			$body  = $xpath->query( '/html/body' );

			// Save HTML fragment
			libxml_use_internal_errors( false );
			$phpinfo_html = $html->saveXml( $body->item( 0 ) );
			echo '<div class="table-responsive-lg p-2 bg-light">';
			echo $phpinfo_html;
			echo '</div>';
		}
		?>

    </div>
</div>