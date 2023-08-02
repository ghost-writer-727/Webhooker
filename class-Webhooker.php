<?php defined( 'ABSPATH' ) ||  exit; // Exit if accessed directly
/** 
 * Webhooker
 * @version 1.0.0
 * A simple class to simplify sending data to Zapier and other webhook services.
 */

if( ! class_exists( 'Webhooker' ) ):
class Webhooker {
	/**
	 * Send data to Zapier
	 * @param string $webhookUrl The webhook URL
	 * @param array $data The data to send
	 * @return array
	 */
	public static function send($webhookUrl, $data = []) {
		// Validate the webhook URL
        if (!filter_var($webhookUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Invalid webhook URL.');
        }

        // Validate the data
        if (!is_array($data) && !is_string($data)) {
            throw new InvalidArgumentException('Data must be an array or a string.');
        }
		
		$ch = curl_init($webhookUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_POST, 1);

		$response = curl_exec($ch);

		if ($response === false) {
			throw new Exception('cURL error: ' . curl_error($ch));
		}
		
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return [
			'success' => $httpCode == 200,
			'response' => $response,
			'http_code' => $httpCode
		];
	}
}

endif;
