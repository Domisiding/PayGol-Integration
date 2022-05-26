<?php
class WC_PG_Gateway extends WC_Payment_Gateway {
public function __construct() {
    $this->id			      	= 'paygol';
    $this->icon 		    	= apply_filters('woocommerce_pg_icon',plugins_url() . "/" . plugin_basename(dirname(__FILE__)) . '/images/paygol.png');
    $this->has_fields 			= false; // 
    $this->method_title   		= __( 'Paygol', "PG-Integration" );
    $this->pgintegration_form_fields();
    $this->init_settings();
    $this->title 		    	= apply_filters( 'woopg_title', __( 'Paygol','PG-Integration') );
    $this->method_description    		= apply_filters( 'woopg_description', __( 'Paygol bietet Ihnen weltweite Deckung mit einer vollständigen Zahlungslösung.','PG-Integration' ) );
    $this->serviceID      		= $this->get_option('serviceID') ;
    $this->secretKEY      		= $this->get_option('secretKEY') ;
    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    add_action( 'woocommerce_receipt_paygol', array( $this, 'receipt_page' ) );
    add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( &$this, 'pgintegration_ipn_response') );
    add_action ('woocommerce_thankyou',array($this,'order_received'),1); 
	}
  //////////////////////////////////////////////////////////////////////  
  	function pgintegration_ipn_response(){
		global $woocommerce; //
		$get_filtered 	= filter_input_array(INPUT_GET);
		$order_id 	= $get_filtered['custom'];  
		$key 		= $get_filtered['key'];  
		$sid 		= $get_filtered['service_id'];  
		$order 		= new WC_Order( $order_id );
		$service_id 	= $this->serviceID;  
		$secret_key 	= $this->secretKEY;  
		$status 	= $order->get_status(); 
    	
		/////
		if  ($key != $secret_key)
		{ 	echo "Validierungsfehler"; exit; }            
		////
		if  ($sid != $service_id) 
		{ 	echo "Validierungsfehler"; exit; }            
		////
		
		if ($status!="cancelled") // 
		 {
			if( ($get_filtered['frmprice'] == $order->get_total() and $get_filtered['frmcurrency'] == get_woocommerce_currency() ) and $get_filtered['service_id'] == $service_id)
			  {                           
			  $order->update_status('processing'); ///              
			  $order->reduce_order_stock();
			  $woocommerce->cart->empty_cart();           
			  }
			else
			  { 
			  $order->update_status('pending'); /// 
			  
			  } 
		}else{     // == cancelled
				if( ($get_filtered['frmprice'] == $order->get_total() and $get_filtered['frmcurrency'] == get_woocommerce_currency() ) and $get_filtered['service_id'] == $service_id)
				{                           
				  $order->update_status('processing'); ///              
				  $order->reduce_order_stock();
				  $woocommerce->cart->empty_cart();           
				}
			  else
				{ 
				$order->update_status('pending'); /// 
				//
				//
				} 
		} 
	} 
  //////////////////////////////////////////////////////////////////////                                                                     
  function pgintegration_form_fields() {
     // 
     $this->form_fields = array(
    		'enabled' 	=> array(
    		'title' 	=> __( 'Aktivieren/Deaktivieren', 'PG-Integration' ),
    		'type' 		=> 'checkbox',
    		'label' 	=> __( 'Aktivieren Sie Paygol-Zahlungen', 'PG-Integration' ),
    		'default' 	=> 'yes'
    	),
    		'serviceID' 	=> array(
    		'title' 	=> __( 'Service ID', 'PayGol-WooCommerce' ),
    		'type' 		=> 'text',
    		'description' 	=> __( 'Dies ist die ID Ihres Paygol-Dienstes.', 'PG-Integration' ),
    		'default' 	=> __( '', 'PayGol-WooCommerce' ),
    		'desc_tip'      => true,
    	),
		'secretKEY' 	=> array(
    		'title' 	=> __( 'Secret Key', 'PayGol-WooCommerce' ),
    		'type' 		=> 'text',
    		'description' 	=> __( 'Dies ist der geheime Schlüssel Ihres Paygol-Dienstes.', 'PG-Integration' ),
    		'default' 	=> __( '', 'PayGol-WooCommerce' ),
    		'desc_tip'      => true,
    	)
    );
  }
  //////////////////////////////////////////////////////////////////////   
  public function admin_options() {
    // 
		?>
		<h3><?php _e( 'Paygol', 'PG-Integration' ); ?></h3> 	
		<table class="form-table">
			<?php $this->generate_settings_html(); ?>
      <tr valign="top">
        <th scope="row" class="titledesc"><?php _e('Payments notification URL (IPN)','PG-Integration');?></th>
        <td class="forminp"><b><?php _e(add_query_arg( 'wc-api', 'WC_Paygol_Gateway', home_url( '/' )),'PG-Integration'); ?></b><br>
        <span class="description"><?php _e('Fügen Sie die "Payments notification URL (IPN)" in ihr Paygol Konto hinzu.','PG-Integration'); ?></span>
        </td>
      </tr>			
		</table> 	
		<?php
	 }
 
  //////////////////////////////////////////////////////////////////////   
   function receipt_page($order) {
    echo '<p>'.__( 'Klicken Sie auf die Paygol-Schaltfläche, um mit dem Kauf fortzufahren', "PG-Integration" ).'</p>';
    echo $this->generate_pgintegration_form($order); 
	}
  //////////////////////////////////////////////////////////////////////////////
 function generate_pgintegration_form($orderID) {
		// Paygol form 
		global $woocommerce;
		$order 		   = new WC_Order($orderID);
		$gateway_address   = 'https://www.paygol.com/pay'; 
		$paygol_args       = $this->prepare_args($order);
		//$paygol_args_array = array();
		foreach ($paygol_args as $key => $value) { 
			$paygol_args_array[] = '<input type="hidden" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
		}
     	return '<form action="'.esc_url($gateway_address).'" method="post" name="pg_frm" id="pg_frm" target="_top">
			' . implode( '', $paygol_args_array) . '      
			<button type="submit" style="background-color:transparent" name="pg_button" id="pg_button"><img src="'.(substr(get_locale(),0,2) =='es'? plugins_url().'/'.plugin_basename(dirname(__FILE__)).'/images/paygol_es_white.png':plugins_url().'/'.plugin_basename(dirname(__FILE__)).'/images/paygol_en_white.png').'" border="0" alt="Make payments Paygol: the easiest way!" title="Zahlungen leisten Paygol: der einfachste Weg!" /></button>
			</form>';
    }
    
  ////////////////////////////////////////////////////////////////////////////// 
  function prepare_args( $order ) {
		global $woocommerce;
		$orderID = $order->id;  // Bestellnummer              
		$shopOrderInfo = get_bloginfo('name').' | Order #'.$orderID; //  
		add_query_arg( 'wc-api', 'WC_Paygol_Gateway', home_url( '/' ) ); 		                 
		$args = array (
				'pg_serviceid'	=> $this->serviceID,         //
				'pg_currency'	=> get_woocommerce_currency(),  // 
				'pg_name'	=> $shopOrderInfo,    //   
				'pg_custom'     => $orderID,       // 
				'pg_price'	=> $order->get_total(), // 
				'pg_return_url'	=> apply_filters( 'paygol_param_urlOK', $this->get_return_url( $order )), // Erfolgreich URL
				'pg_cancel_url'	=> apply_filters( 'paygol_param_urlKO', $order->get_checkout_payment_url())	// Abgebrochen URL            
		);		
		return $args;		
	} 
  ////////////////////////////////////////////////////////////////////////////// 
  function process_payment( $order_id ) {
      global $woocommerce; 
	  $order = new WC_Order( $order_id );
      return array(
		'result' 	=> 'success',
		'redirect'	=> $order->get_checkout_payment_url( true ));     
    } 
  //////////////////////////////////////////////////////////////////////////////
      function order_received($order_id){          
        $order = new WC_Order( $order_id );
        switch($order->get_status()){                 
          case 'completed':{
               wc_print_notice( __( 'Ihre Bestellung wurde abgeschlossen.', 'PG-Integration' ), 'success' );
               break;
          }             
          case 'cancelled':{
               wc_print_notice( __( 'Ihre Bestellung kann nicht abgeschlossen werden.', 'PG-Integration' ), 'error' );
               break;         
          } 
         default:{
              $order->update_status('processing');
              wc_print_notice( __( 'Ihre Anfrage wird bearbeitet und abgeschlossen, sobald sie vom lokalen Zahlungsanbieter bestätigt wurde.', 'PG-Integration' ), 'notice' );          
          
          }              
        }
              
    }
  ////////////////////////////////////////////////////////////////////////////// 
 }  
?>
