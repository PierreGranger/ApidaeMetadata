<?php

namespace PierreGranger;

use PierreGranger\ApidaeCore;
use PierreGranger\ApidaeException;

/**
 *
 * @author  Pierre Granger <pierre.granger@apidae-tourisme.com>
 *
 */

class ApidaeMetadata extends ApidaeCore
{
    /**
     * clientId par défaut, fourni lors de l'implémentation de l'objet (new ...). S'il n'est pas renseigné il pourra être passé en paramètre des classes qui en ont besoin
     * Format GUID. Ex : b2d52993-9712-4346-8108-d67dfc51dea0
     * @var	string
     */
    protected $clientId;
    /**
     * secret par défaut, fourni lors de l'implémentation de l'objet (new ...). S'il n'est pas renseigné il pourra être passé en paramètre des classes qui en ont besoin
     * 15 caractères alphanumériques. Ex : aOzHUt3H227zivv
     * @var	string
     */
    protected $secret;

    protected const TARGETS = ['general', 'membre', 'projet'];

    public function __construct(array $params = null)
    {
        parent::__construct($params);

        if (!is_array($params)) {
            $params = [];
        }

        if (isset($params['clientId'])) {
            $this->clientId = $params['clientId'];
        }
        if (isset($params['secret'])) {
            $this->secret = $params['secret'];
        }
    }

    public function get($id, $noeud, $params = null)
    {
        if (!is_array($params)) {
            $params  = [];
        }

        $clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId;
        $secret = isset($params['secret']) ? $params['secret'] : $this->secret;

        if ($clientId == null) {
            throw new ApidaeException('clientId non renseigné', ApidaeException::MISSING_PARAMETER);
        }
        if ($secret == null) {
            throw new ApidaeException('secret non renseigné', ApidaeException::MISSING_PARAMETER);
        }

        $access_token = $this->gimme_token($clientId, $secret);
        if (!$access_token) {
            throw new ApidaeException(__LINE__ . 'Impossible de récupérer le token d\'écriture');
        }

        $requestParams = [
            'token' => $access_token,
            'CUSTOMREQUEST' => 'GET'
        ] ;
        if ( isset($params['header']) ) $requestParams['header'] = $params['header'] ;

        $result = $this->request('/api/v002/metadata/' . $id . '/' . $noeud . '/', $requestParams);

        if ($result['code'] != 200) {
            throw new ApidaeException('Retour http incorrect', ApidaeException::INVALID_HTTPCODE, [
                'debug' => $this->debug,
                'result' => $result
            ]);
        }
        return $result['body'];
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

    public function putMultiple($query, array $params = null)
    {
        if (!is_array($params)) {
            $params  = [];
        }

        $clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId;
        $secret = isset($params['secret']) ? $params['secret'] : $this->secret;

        if ($clientId == null) {
            throw new ApidaeException('clientId non renseigné', ApidaeException::MISSING_PARAMETER);
        }
        if ($secret == null) {
            throw new ApidaeException('secret non renseigné', ApidaeException::MISSING_PARAMETER);
        }

        $access_token = $this->gimme_token($clientId, $secret);
        if (!$access_token) {
            throw new ApidaeException(__LINE__ . 'Impossible de récupérer le token d\'écriture');
        }

        $requestParams = [
            'token' => $access_token,
            'POSTFIELDS' => ['query' => json_encode($query)],
            'CUSTOMREQUEST' => 'PUT'
        ] ;
        if ( isset($params['header']) ) $requestParams['header'] = $params['header'] ;

        $result = $this->request('/api/v002/metadata/', $requestParams);

        if ($result['code'] != 200) {
            throw new ApidaeException('Retour http incorrect', ApidaeException::INVALID_HTTPCODE, [
                'debug' => $this->debug,
                'result' => $result
            ]);
        }

        return true;
    }

    public function put(int $id, string $noeud, array|string $postfields, array $params = null)
    {
        if (!is_array($params)) {
            $params  = [];
        }

        $clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId;
        $secret = isset($params['secret']) ? $params['secret'] : $this->secret;

        if ($clientId == null) {
            throw new ApidaeException('clientId non renseigné', ApidaeException::MISSING_PARAMETER);
        }
        if ($secret == null) {
            throw new ApidaeException('secret non renseigné', ApidaeException::MISSING_PARAMETER);
        }

        $access_token = $this->gimme_token($clientId, $secret);
        if (!$access_token) {
            throw new ApidaeException(__LINE__ . 'Impossible de récupérer le token d\'écriture');
        }

        $requestParams = [
            'token' => $access_token,
            'POSTFIELDS' => $postfields,
            'CUSTOMREQUEST' => 'PUT'
        ] ;
        if ( isset($params['header']) ) $requestParams['header'] = $params['header'] ;

        $result = $this->request('/api/v002/metadata/' . $id . '/' . $noeud . '/', $requestParams);

        if ($result['code'] != 200) {
            throw new ApidaeException('Retour http incorrect', ApidaeException::INVALID_HTTPCODE, [
                'debug' => $this->debug,
                'result' => $result
            ]);
        }

        return true;
    }

    public function delete($id, $noeud, $params = null)
    {
        if (!is_array($params)) {
            $params  = [];
        }

        $clientId = isset($params['clientId']) ? $params['clientId'] : $this->clientId;
        $secret = isset($params['secret']) ? $params['secret'] : $this->secret;

        if ($clientId == null) {
            throw new ApidaeException('clientId non renseigné', ApidaeException::MISSING_PARAMETER);
        }
        if ($secret == null) {
            throw new ApidaeException('secret non renseigné', ApidaeException::MISSING_PARAMETER);
        }

        $access_token = $this->gimme_token($clientId, $secret);
        if (!$access_token) {
            throw new ApidaeException(__LINE__ . 'Impossible de récupérer le token d\'écriture');
        }

        $curl_url = '/api/v002/metadata/' . $id . '/' . $noeud . '/';
        if (isset($params['targetType'])) {
            if (!in_array($params['targetType'], self::TARGETS)) {
                throw new \Exception('targetType not in ' . implode(', ', self::TARGETS));
            } else {
                $curl_url .= $params['targetType'] . '/';
            }
        }
        if (isset($params['targetId'])) {
            if (!isset($params['targetType'])) {
                throw new \Exception('targetId defined : targetType required');
            }
            if (!preg_match('#^[0-9]+$#', $params['targetId'])) {
                throw new \Exception('targetId is not a number');
            } else {
                $curl_url .= (int)$params['targetId'] . '/';
            }
        }

        $requestParams = [
            'token' => $access_token,
            'CUSTOMREQUEST' => 'DELETE'
        ] ;
        if ( isset($params['header']) ) $requestParams['header'] = $params['header'] ;

        $result = $this->request($curl_url, $requestParams);

        if ($result['code'] != 200) {
            throw new ApidaeException('Retour http incorrect', ApidaeException::INVALID_HTTPCODE, [
                'debug' => $this->debug,
                'result' => $result
            ]);
        }
        return true;
    }
}
