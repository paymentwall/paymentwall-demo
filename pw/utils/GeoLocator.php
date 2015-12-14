<?php

class GeoLocator {
	public function locate($ip)
	{
		$parameters = array(
			'key' => PUBLIC_KEY,
			'uid' => USER_ID,
			'user_ip' => $ip
		);

		$url = 'https://api.paymentwall.com/api/rest/country?' . http_build_query($parameters);

		$result = file_get_contents($url);
		$country = json_decode($result, true);

		return $country;
	}
}