<?php
	namespace Sinsituwoka;

	use GuzzleHttp\Client;
	class Sinsituwoka
	{

		public function __construct()
		{
			try {
				$this->client_secret = self::clientSecret();
				$this->client_id = $this->client_secret->installed->client_id;
				$this->client_secret_str = $this->client_secret->installed->client_secret;
				$this->redirect_uri = $this->client_secret->installed->redirect_uris[0];
				$this->uri = self::uri();
				$this->authorization_code = self::authorizationCode();
				$this->access_token = self::accessTokenFromLocal();
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
			}
		}

		public function clientSecret()
		{
			$client_secret_file = dirname(__FILE__) . '/../constants/client_secret.json';

			try {
				if (file_exists($client_secret_file)) {
					$client_secret_json = file_get_contents($client_secret_file);
					$client_secret_obj = json_decode($client_secret_json);
					return $client_secret_obj;
				} else {
					throw new \Exception('Error: ../constants/client_secret.json doesn\'t exist.');
				}
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
				throw new \Exception;
			}
		}

		public function authorizationCode()
		{
			$authorization_code_file = dirname(__FILE__) . '/../constants/authorization_code';
			$authorization_code = rtrim(file_get_contents($authorization_code_file));
			return $authorization_code;
		}

		public function uri()
		{
			$scope_file = dirname(__FILE__) . '/../constants/scope';
			try {
				if (file_exists($scope_file)) {
					$scope = rtrim(file_get_contents($scope_file));
					$query = http_build_query(
						[
							'response_type' => 'code',
								'client_id' => $this->client_id,
								'redirect_uri' => $this->redirect_uri,
								'scope' => $scope,
								'access_type' => 'offline',
						]);
					$uri = 'https://accounts.google.com/o/oauth2/v2/auth?'.$query;
					return $uri;
				} else {
					throw new \Exception('Error: ../constants/scope doesn\'t exist.');
				}
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
			}
		}


		public function accessTokenFromRemote()
		{
			$client = new Client([
				'base_uri' => 'https://www.googleapis.com/oauth2/v4/token',
					'timeout'  => 2.0,
			]);

			$response = $client->request('POST', '', [
				'form_params' => [
					'code' => $this->authorization_code,
						'client_id' => $this->client_id,
						'client_secret' => $this->client_secret_str,
						'redirect_uri' => $this->redirect_uri,
						'grant_type' => 'authorization_code',
						'access_type' => 'offline',
				]
			]);
			return $response->getBody();
		}

		public function accessTokenFromLocal()
		{
			$access_token_file = dirname(__FILE__) . '/../constants/access_token.json';

			try {
				if (file_exists($access_token_file)) {
					$access_token_json = file_get_contents($access_token_file);
					$access_token_obj = json_decode($access_token_json);
					return $access_token_obj;
				} else {
					throw new \Exception('Error: ../constants/access_token.json doesn\'t exist.');
				}
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
				throw new \Exception('Error in accessTokenFromLocal');
			}
		}


		public function refresh()
		{
			try {
				$secret = $this->client_secret;
				$access_token = self::accessTokenFromLocal();
				$refresh_token = $access_token->refresh_token;
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
			}
			
			$client = new Client([
				'base_uri' => 'https://www.googleapis.com/oauth2/v4/token',
					'timeout'  => 2.0,
			]);

			$response = $client->request('POST', '', [
				'form_params' => [
					'refresh_token' => $refresh_token,
						'client_id' => $this->client_id,
						'client_secret' => $this->client_secret_str,
						'grant_type' => 'refresh_token',
				]
			]);
			$refreshed_token_json = $response->getBody();
			$refreshed_token_obj = json_decode($refreshed_token_json);
			return $refreshed_token_obj;
		}

		public function bearer()
		{
			try {
				$refreshed_token_obj = self::refresh();
			} catch (\Exception $e) {
				echo $e->getMessage(), "\n";
			}
			$bearer = $refreshed_token_obj->access_token;
			return $bearer;
		}

	}
