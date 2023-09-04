<?php

namespace App\Repositories;

use App\Interfaces\CertificateRepositoryInterface;
use App\Models\Certificate;
use Illuminate\Http\Request;

/**
 * Class CertificateRepository.
 */
class CertificateRepository implements CertificateRepositoryInterface
{
    public $issuer;
    
    /**
     * Store uploaded file
     */
    public function create(Request $request)
    {
        $fileHasErrors = $this->validateFile($request);
        $result = [];

        if (empty($fileHasErrors['errors'])) {
            
            $result = [
                'data'  =>  [
                    'status'    =>  200,
                    'issuer'    =>  $this->issuer,
                    'code'      =>  'Verified',
                    'result'    =>  'Verified'
                ]
            ];

        } else {
            
            $result = [
                'data'  =>  [
                    'status'    =>  200,
                    'error'     => 'Invalid file',
                    'code'      =>  $fileHasErrors['errors']['error_codes'],
                    'result'    => $fileHasErrors['errors']['error_codes']
                ]
            ];
            
        }
        \Log::info(auth('sanctum')->user()->id);
        \Log::info(auth('sanctum')->check());
        Certificate::create([
            'user_id'               =>  \Auth::user()->id,
            'file_type'             =>  'json',
            'verification_result'   =>  $result,
        ]);

        return response()->json($result);
    }


    /**
     * Validate file
     */
    private function validateFile($request)
    {
        $fileContent = json_decode(file_get_contents($request->file->getRealPath()), true);
        $dnsAPI = json_decode(file_get_contents('https://dns.google/resolve?name=ropstore.accredify.io&type=TXT'), true);
        $errors = [];

        if (isset($fileContent)) {

            if (isset($fileContent['data']['issuer']['name'])) {
                $this->issuer = $fileContent['data']['issuer']['name'];
            }

            //recipient validation
            if (!isset($fileContent['data']['recipient']['email']) &&
                !isset($fileContent['data']['recipient']['name']) &&
                empty(isset($fileContent['data']['recipient']['email'])) &&
                empty(isset($fileContent['data']['recipient']['name'])) 
            ) {
                $errors['error_codes'][] = 'invalid_recipient';
            }

            //issuer validation
            if ((!isset($fileContent['data']['issuer']['name']) &&
                !isset($fileContent['data']['issuer']['identityProof']) &&
                empty(isset($fileContent['data']['issuer']['name'])) &&
                empty(isset($fileContent['data']['issuer']['identityProof'])) || 
                ((isset($fileContent['data']['issuer']['identityProof'])) && 
                $this->checkIssuer($dnsAPI, $fileContent['data']['issuer']['identityProof']) === false)) 
            ) {
                $errors['error_codes'][] = 'invalid_issuer';
            }

            //signature validation
            if ((!isset($fileContent['signature']['type']) &&
                !isset($fileContent['signature']['targetHash']) &&
                empty($fileContent['signature']['type']) &&
                empty($fileContent['signature']['targetHash']) || 
                ((isset($fileContent['signature']['targetHash'])) && 
                $this->checkSignature($fileContent['signature'], $fileContent['data']) === false))
            ) {
                $errors['error_codes'][] = 'invalid_signature';
            }

        }
        
        return [
            'file'      =>  $fileContent,
            'errors'    =>  $errors
        ];
    }


    /**
     * Check issuer
     */
    private function checkIssuer($dnsAPI, $identityProof)
    {
        $valid = false;
            
        if (isset($dnsAPI['Answer'])) {
            $match = \Arr::where($dnsAPI['Answer'], function ($value, $key) use ($identityProof) {
                return $value['name'] === $identityProof['location'] . ".";
            });

            $valid = count($match) ? true : false;  
        }

        return $valid;
    }


    /**
     * Check signature
     */
    private function checkSignature($signature, $data) 
    {
        $revisedData = array_values([
            hash('sha256', json_encode(['id'     =>  $data['id']])),
            hash('sha256', json_encode(['name'   =>  $data['name']])),
            hash('sha256', json_encode(['recipient.name'    =>  $data['recipient']['name']])),
            hash('sha256', json_encode(['recipient.email'   =>  $data['recipient']['email']])),
            hash('sha256', json_encode(['issuer.name'   =>  $data['issuer']['name']])),
            hash('sha256', json_encode(['issuer.identityProof.type'   =>  $data['issuer']['identityProof']['type']])),
            hash('sha256', json_encode(['issuer.identityProof.key'   =>  $data['issuer']['identityProof']['key']])),
            hash('sha256', json_encode(['issuer.identityProof.location'   =>  $data['issuer']['identityProof']['location']])),
            hash('sha256', json_encode(['issued'    =>  $data['issued']]))
        ]);
        
        sort($revisedData);
        $hashedKeys = hash('sha256', json_encode(array_values($revisedData)));
        
        return ($hashedKeys === $signature['targetHash']) ? true : false;
    }
}
