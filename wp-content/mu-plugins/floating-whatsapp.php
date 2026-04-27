<?php
/**
 * Plugin Name: Floating WhatsApp Button
 * Description: Adds a fixed WhatsApp button to the front end.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fzthasancelik_floating_whatsapp_button() {
	if ( is_admin() ) {
		return;
	}

	$phone_number = defined( 'FZTHASANCELIK_WHATSAPP_NUMBER' ) ? FZTHASANCELIK_WHATSAPP_NUMBER : '905435439307';
	$message      = rawurlencode( 'Merhaba, bilgi almak istiyorum.' );
	$url          = 'https://wa.me/' . preg_replace( '/\D+/', '', $phone_number ) . '?text=' . $message;
	?>
	<a class="fzthasancelik-whatsapp-float" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp ile iletisime gec">
		<svg aria-hidden="true" viewBox="0 0 32 32" focusable="false">
			<path d="M16.01 3.2c-7.03 0-12.75 5.72-12.75 12.75 0 2.25.59 4.45 1.72 6.39L3.15 29l6.82-1.79a12.7 12.7 0 0 0 6.04 1.54c7.03 0 12.75-5.72 12.75-12.75S23.04 3.2 16.01 3.2Zm0 23.39c-1.87 0-3.7-.49-5.3-1.42l-.38-.22-4.05 1.06 1.08-3.94-.25-.4a10.56 10.56 0 0 1-1.62-5.62c0-5.82 4.74-10.56 10.56-10.56s10.56 4.74 10.56 10.56-4.78 10.54-10.6 10.54Zm5.79-7.9c-.32-.16-1.88-.93-2.17-1.03-.29-.11-.5-.16-.71.16-.21.32-.82 1.03-1 1.24-.18.21-.37.24-.69.08-.32-.16-1.34-.49-2.55-1.57-.94-.84-1.58-1.88-1.77-2.2-.18-.32-.02-.49.14-.65.14-.14.32-.37.48-.55.16-.18.21-.32.32-.53.11-.21.05-.4-.03-.55-.08-.16-.71-1.72-.98-2.36-.26-.62-.52-.54-.71-.55h-.61c-.21 0-.55.08-.84.4-.29.32-1.1 1.08-1.1 2.62s1.13 3.04 1.29 3.25c.16.21 2.22 3.39 5.38 4.75.75.32 1.34.52 1.8.66.76.24 1.44.2 1.98.12.6-.09 1.88-.77 2.14-1.51.26-.74.26-1.38.18-1.51-.08-.13-.29-.21-.61-.37Z"/>
		</svg>
	</a>
	<style>
		.fzthasancelik-whatsapp-float {
			position: fixed;
			right: 24px;
			bottom: 24px;
			z-index: 99999;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 58px;
			height: 58px;
			color: #fff;
			background: #25d366;
			border-radius: 50%;
			box-shadow: 0 10px 24px rgba(0, 0, 0, .22);
			transition: transform .2s ease, box-shadow .2s ease, background-color .2s ease;
		}

		.fzthasancelik-whatsapp-float:hover,
		.fzthasancelik-whatsapp-float:focus {
			color: #fff;
			background: #1ebe5d;
			transform: translateY(-2px);
			box-shadow: 0 14px 30px rgba(0, 0, 0, .28);
		}

		.fzthasancelik-whatsapp-float:focus-visible {
			outline: 3px solid #fff;
			outline-offset: 3px;
		}

		.fzthasancelik-whatsapp-float svg {
			display: block;
			width: 32px;
			height: 32px;
			fill: currentColor;
		}

		@media (max-width: 767px) {
			.fzthasancelik-whatsapp-float {
				right: 16px;
				bottom: 16px;
				width: 54px;
				height: 54px;
			}
		}
	</style>
	<?php
}
add_action( 'wp_footer', 'fzthasancelik_floating_whatsapp_button', 30 );
