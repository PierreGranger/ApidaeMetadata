<?php

	namespace PierreGranger ;
	use PierreGranger\ApidaeCore ;
/**
*
* @author  Pierre Granger <pierre.granger@apidae-tourisme.com>
*
*/

	class ApidaeMetadata extends ApidaeCore {

		/**
		 * clientId par défaut, fourni lors de l'implémentation de l'objet (new ...). S'il n'est pas renseigné il pourra être passé en paramètre des classes qui en ont besoin
		 * Format GUID. Ex : b2d52993-9712-4346-8108-d67dfc51dea0
		 * @var	string
		 */
		protected $clientId ;
		/** 
		 * secret par défaut, fourni lors de l'implémentation de l'objet (new ...). S'il n'est pas renseigné il pourra être passé en paramètre des classes qui en ont besoin
		 * 15 caractères alphanumériques. Ex : aOzHUt3H227zivv
		 * @var	string
		 */
		protected $secret ;

		protected const targets = Array('general','membre','projet') ;

		public function __construct(array $params=null) {

			parent::__construct($params) ;

			if ( ! is_array($params) ) $params = Array() ;
			
			if ( isset($params['clientId']) ) $this->clientId = $params['clientId'] ;
			if ( isset($params['secret']) ) $this->secret = $params['secret'] ;

		}

		public function get($id,$noeud,$params=null) {
			
			if ( ! is_array($params) ) $params  = Array() ;

			$clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId ;
			$secret = isset($params['secret']) ? $params['secret'] : $this->secret ;

			if ( $clientId == null ) throw new \Exception(__LINE__.':clientId non renseigné') ;
			if ( $secret == null ) throw new \Exception(__LINE__.':secret non renseigné') ;

			$access_token = $this->gimme_token($clientId,$secret) ;
			if ( ! $access_token ) throw new \Exception(__LINE__.'Impossible de récupérer le token d\'écriture') ;
				
			$ch = curl_init();
			
			$curl_url = $this->url_api().'api/v002/metadata/'.$id.'/'.$noeud.'/' ;
			curl_setopt($ch,CURLOPT_URL,$curl_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer ".$access_token));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
			
			$result = curl_exec($ch);
			
			if (FALSE === $result) throw new \Exception(curl_error($ch), curl_errno($ch));
			
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $header_size);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_infos = curl_getinfo($ch) ;
			$body = substr($result, $header_size) ;

			if ( $http_code != 200 ) throw new \Exception('Retour http incorrect',$http_code) ;
			return $result ;
		}

		/**
		 * @param	int	$id	Identiant de l'offre. Ex : 4683815
		 * @param	string	$noeud	Nom du noeud. Ex : open-system
		 * @param	string	$data	Métadonnées, au format récupéré dans un objet en lecture. Ex : {}
		 * @param	null|array	$params	Paramètres additionnels
		 * 						$params['clientId']
		 * 						$params['secret']
		 */

		/*
			Exemple de métadonnée
			{
				"widget": {
					"v": "1.0",
					"uis": [
						{
							"ui": "CA-ITEAG-29234-G4797",
							"metier": "hebergement"
						}
					],
					"integration": {
						"id": 370,
						"idPanier": "FBgUFBQ"
					}
				}
			}
		*/

		public function put($id,$noeud,$postfields,$params=null) {

			if ( ! is_array($params) ) $params  = Array() ;
			
			$clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId ;
			$secret = isset($params['secret']) ? $params['secret'] : $this->secret ;

			if ( $clientId == null ) throw new \Exception(__LINE__.':clientId non renseigné') ;
			if ( $secret == null ) throw new \Exception(__LINE__.':secret non renseigné') ;

			$access_token = $this->gimme_token($clientId,$secret) ;
			if ( ! $access_token ) throw new \Exception(__LINE__.'Impossible de récupérer le token d\'écriture') ;
			
			$body = null ;
			$http_code = null ;

			$ch = curl_init();
			$curl_url = $this->url_api().'api/v002/metadata/'.$id.'/'.$noeud.'/' ;
			curl_setopt($ch,CURLOPT_URL,$curl_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer ".$access_token));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $postfields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			// http://dev.apidae-tourisme.com/fr/documentation-technique/v2/oauth/authentification-avec-un-token-oauth2
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
			curl_setopt($ch, CURLOPT_HEADER, true) ;
			
			$result = curl_exec($ch);
			if (FALSE === $result) throw new \Exception(curl_error($ch), curl_errno($ch));
			
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $header_size);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_infos = curl_getinfo($ch) ;
			$body = substr($result, $header_size) ;
			curl_close($ch) ;

			if ( $http_code != 200 ) throw new \Exception('Retour http incorrect',$http_code) ;
			return true ;
		}

		public function delete($id,$noeud,$params=null) {
			if ( ! is_array($params) ) $params  = Array() ;
			
			$clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId ;
			$secret = isset($params['secret']) ? $params['secret'] : $this->secret ;

			if ( $clientId == null ) throw new \Exception(__LINE__.': clientId non renseigné') ;
			if ( $secret == null ) throw new \Exception(__LINE__.': secret non renseigné') ;

			$access_token = $this->gimme_token($clientId,$secret) ;
			if ( ! $access_token )throw new \Exception(__LINE__.'Impossible de récupérer le token d\'écriture') ;
			
			$body = null ;
			$http_code = null ;

			$curl_url = $this->url_api().'api/v002/metadata/'.$id.'/'.$noeud.'/' ;
			if ( isset($params['targetType']) )
			{
				if ( ! in_array($params['targetType'],self::$targets) ) throw new \Exception('targetType not in '.implode(', ',self::targets)) ;
				else $curl_url .= $params['targetType'].'/' ;
			}
			if ( isset($params['targetId']) )
			{
				if ( ! isset($params['targetType']) ) throw new \Exception('targetId defined : targetType required') ;
				if ( ! preg_match('#^[0-9]+$#',$params['targetId']) ) throw new \Exception('targetId is not a number') ;
				else $curl_url .= (int)$params['targetId'].'/' ;
			}

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$curl_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Authorization: Bearer ".$access_token));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			// http://dev.apidae-tourisme.com/fr/documentation-technique/v2/oauth/authentification-avec-un-token-oauth2
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
			curl_setopt($ch, CURLOPT_HEADER, true) ;
			
			$result = curl_exec($ch);
			if (FALSE === $result) throw new \Exception(curl_error($ch), curl_errno($ch));
			
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($result, 0, $header_size);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$curl_infos = curl_getinfo($ch) ;
			$body = substr($result, $header_size) ;
			curl_close($ch) ;

			if ( $http_code != 200 ) throw new \Exception('Retour http incorrect',$http_code) ;
			return true ;
		}

	}
