=== Gateway Paygol Integration ===

Contributors: Domisiding
Tags: paygol, woocommerce, payment, gateway, credit, card, credit card, webpay, tarjeta, paysafecard, oxxo, boleto, bitcoin, sms, shortcode, keyword, sms premium, sms billing, paygol, worldwide payments, e-commerce, ecommerce, mobile payments, pay by phone, pay by sms, pay per call
Requires at least: 3.7.0
Tested up to: 5.9
Stable tag: 1.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Paygol ist ein Online-Zahlungsdienstleister, der eine Vielzahl von weltweiten und lokalen Zahlungsmethoden anbietet.

== Description ==

Paygol ist ein Online-Zahlungsdienstleister, der eine Vielzahl von weltweiten und lokalen Zahlungsmethoden anbietet, einschließlich (aber nicht beschränkt auf)
Kreditkarte, Debitkarte, Überweisung und Barzahlung. Zu den unterstützten lokalen Zahlungsmethoden gehören WebPay, OXXO, Boleto, DineroMail und MercadoPago
und viele andere. Die Einfachheit der Integration macht es für jeden sehr einfach, es zu verwenden.

Webseite:         https://domisiding.de

Zahlungsmethoden: https://www.paygol.com/pricing

== Installation ==

- Sie benötigen eine funktionierende WordPress-Installation mit dem WooCommerce-Plugin.

- Sie benötigen ein Paygol Konto 
  Ein Konto könnnen Sie unter https://www.paygol.com/register erstellen.

- Gehen Sie zu "Plugins -> Hinzufügen".
  Sie müssen auf "Plugin hochladen" klicken, um die Zip-Datei des Plugins manuell hochzuladen. Danach wird sie automatisch installiert.
  Wenn dies aus irgendeinem Grund fehlschlägt, können Sie den Ordner des Plugins auch manuell extrahieren und in folgenden Ordner hochladen wp-content/plugins/.
  
- Aktivieren Sie in Ihrem WordPress-Plugin-Bereich das Paygol-Plugin.

- Gehen Sie zur Checkout-Konfiguration und fahren Sie mit der Konfiguration des Plugins fort:
  * Die Textfelder enthalten den Text, der während des Bestellvorgangs verwendet wird.
  * Die Service-ID und der geheime Schlüssel finden Sie neben dem Namen Ihres Service unter "Meine Services" in Ihrem Paygol-Konto.
  * Fügen Sie die angegebene IPN-URL in die Einstellung "Hintergrund-URL (IPN)" in der Konfiguration Ihres Dienstes ein
    in Ihrem Paygol-Bedienfeld (klicken Sie auf das Stiftsymbol unter "Meine Dienste", um Ihren Dienst zu bearbeiten).

== Changelog ==

= 1.7 =
* Added WooCommerce is Active Check

= 1.6 =
* Added Multilanguage

= 1.5 =
* Logo Bug fix

= 1.4 =
* Added a Admin Menu Page with Integration Information.

= 1.3 =
* Tested with WordPress 3.7.0 up to 5.4, and WooCommerce 2.3.0 up to 4.0.1
* Small Bug fixes in the IPN

= 1.2 =
* Tested with WordPress 3.7.0 up to 4.7.5, and WooCommerce 2.3.0 up to 3.0.7
* Added validation for payment notifications.

= 1.1 =
* Tested with WordPress 3.7.0 up to 4.7.3, and WooCommerce 2.3.0 up to 2.6.14.
* Updated with new logo.
                                                                
= 1.0 =
* Tested with WordPress 3.7.0 up to 4.6.1, and WooCommerce 2.3.0 up to 2.6.7.

== Recommendations and important notes ==

- Test your service by enabling test mode on your service (the Enabled/Testing button at "My Services", at the Paygol panel).
  Be sure to change it back to "Enabled" once you are done testing.

- Some payment methods provided by Paygol (such as credit card payments) will confirm the payment immediately, so the payer will 
  see the payment status as "Completed". However, other payment methods (such as local cash payment services) may take longer 
  to confirm the payment. In these cases the payer will see the status "Processing". After the payment is confirmed
  by the local payments provider, the status will internally be updated to "Completed". Depending on your specific
  needs, you may want to use the "Hold Stock" WooCommerce setting if you need to make sure that stock is available for payments
  that are not notified immediately.
  
  