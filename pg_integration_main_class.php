<?php
/*
@wordpress-plugin
Plugin Name: Gateway Paygol Integration
Description: Paygol ist ein Online-Zahlungsdienstleister, der eine Vielzahl von weltweiten und lokalen Zahlungsmethoden anbietet, einschließlich (aber nicht beschränkt auf) Kreditkarten, Debitkarten, Banküberweisungen und Barzahlungen. Zu den unterstützten lokalen Zahlungsmethoden gehören WebPay, OXXO, Boleto, DineroMail, MercadoPago und viele andere. Die Einfachheit der Integration macht es für jeden sehr einfach, es zu verwenden, und diese Benutzerfreundlichkeit lässt sich perfekt auf dieses Plugin übertragen.
Author: Domisiding
Version: 1.7
License: GNU General Public License v2
Text Domain: PG-Integration
Author URI: https://domisiding.de
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
////////////////////////////////////////////////////////////// 
$plugin_header_translate = array( __('Gateway Paygol Integration', 'PG-Integration'), __('Ermöglicht Ihren Kunden aus der ganzen Welt das Bezahlen mit einer Vielzahl internationaler und lokaler Zahlungsmethoden, darunter Kreditkarten, Debitkarten (einschließlich Redcompra über WebPay), elektronische Überweisungen, Barzahlungen, OXXO, Boleto Bancario, MercadoPago und vieles mehr mehr über die Paygol Online-Zahlungsplattform.', 'PG-Integration') );
////////////////////////////////////////////////////////////// 
add_action( 'plugins_loaded', 'pgintegration_plugins_loaded' );
add_action( 'plugins_loaded', 'pg_integration_textdomain' );
add_action('admin_menu', 'pgintegration_admin_menu');
////////////////////////////////////////////////////////////// 
function pg_integration_textdomain() {
    load_plugin_textdomain( 'PG-Integration', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
////////////////////////////////////////////////////////////// 
function pgintegration_plugins_loaded() {
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    include_once ('pg_integration_gateway_class.php');
	add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_gateway_pg_gateway' );
}
}
////////////////////////////////////////////////////////////// 
function woocommerce_add_gateway_pg_gateway($methods) {
	$methods[] = 'WC_PG_Gateway';
	return $methods;
}
//////////////////////////// Menü ////////////////////////////
function pgintegration_admin_menu(){
        add_menu_page( 'PayGol', 'PayGol', 'manage_options', 'pg-information', 'pgintegration_info_page', plugins_url( '/images/icon.png', __FILE__ ));
}
///////////////////////// Menü Seite /////////////////////////
function pgintegration_info_page(){
	
        echo "<h1>PayGol Integration</h1>";
		?>
		<div class="card" style="max-width:100%">
            <h3><?php _e('Wo sehe Ich die Service ID und den Secret Key?' ,'PG-Integration'); ?></h3>
					<?php _e('<p></p>Sie benötigen ein Paygol Konto, ein Konto können Sie unter <a href="https://www.paygol.com/en/get-started">https://www.paygol.com/en/get-started</a> erstellen. Wenn Sie sich ein Konto erstellt haben, gehen Sie im Dashboard unter dem Punkt Account auf "Notifications".<hr><p></p>', 'PG-Integration'); ?>
			
				<h3><?php _e('Was muss Ich mit der Service ID und den Secret Key tun?' ,'PG-Integration'); ?></h3>
			            <?php _e('Sie müssen diese beiden Zahlenkombinationen in dem WooCommerce Plugin bei Zahlungen unter PayGol eintragen.<hr><p></p>', 'PG-Integration'); ?>
				
					<h3><?php _e('Was muss Ich mit der IPN URL tun?' ,'PG-Integration'); ?></h3>
							<?php _e('Die IPN URL müssen Sie in dem PayGol-Dashboard eintragen unter dem "Notifications" Bereich.', 'PG-Integration'); ?>
						
						<h3><?php _e('Welche Zahlungsmethoden kann Ich mit PayGol anbieten?' ,'PG-Integration'); ?></h3>
								<?php _e('Mit PayGol ist es möglich die bekannten Zahlungsmethoden wie Paysafecard, Bitcoin und Giropay anzubieten, die gesamten Zahlungsmethoden können Sie unter <a href="https://www.paygol.com/en/pricing">https://www.paygol.com/en/pricing</a> einsehen. <hr><p></p>', 'PG-Integration'); ?>
								
							<h3><?php _e('Wie kann Ich das Design verändern von der PayGol Webcheckout Page?' ,'PG-Integration'); ?></h3>
									<?php _e('Wenn Sie im PayGol-Dashboard sind, können Sie im Reiter Account auf Customization klicken, dort können Sie ihr eigenes Logo einfügen und ihr eigenes Hintergrundbild.', 'PG-Integration'); ?>
								
			</div>
		<?php	

}
//////////////////////////////////////////////////////////////
?>