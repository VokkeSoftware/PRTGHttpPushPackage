<?php

namespace VokkeFlex\PRTGResponder;

/**
 * Class PRTGResponder
 * @package VokkeFlex\PRTGResponder
 */
class PRTGResponder {

	/**
	 * @param $baseUrl
	 * @param $token
	 * @param $isSuccess
	 * @param $message
	 */
	public static function binaryAcknowledgement($baseUrl, $token, $isSuccess, $message){
		/** Below is the PRTG compatible XML */
		if ($isSuccess){
			$content = "
			<prtg>
				<result>
					<channel>Acknowledgement</channel>
					<value>1</value>
					<text>$message</text>
				</result>
			</prtg>";
		}else{
			$content = "
			<prtg>
				<result>
					<channel>Acknowledgement</channel>
					<value>0</value>
				</result>
				<error></error>
				<text>$message</text>
			</prtg>";
		}


		$completeUrl = rtrim($baseUrl, '/') . '/' . $token . '?content=' . urlencode($content);
		$curl = curl_init();

		/** For now we have to turn this off as PRTG is using a self-signed certificate */
		curl_setopt_array($curl, [
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $completeUrl
		]);

		$resp = curl_exec($curl);
		curl_close($curl);
	}

}
