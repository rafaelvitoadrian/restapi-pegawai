<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OtentikasiModel;
use CodeIgniter\API\ResponseTrait;

class Otentikasi extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $validation = \Config\Services::validation();
        $aturan = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'requried' => 'Silahkan masukan email',
                    'valid_email' => 'Silahkan masukan email yang valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan masukan password',
                ]
            ],
        ];

        $validation->setRules($aturan);
        if (!$validation->withRequest($this->request)->run()) {
           return $this->fail($validation->getErrors());
        }

        $otentikasiModel = new OtentikasiModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $otentikasiModel->getEmail($email);
        if($data['password'] != md5($password)) {
           return $this->fail('password tidak sesuai');
        }

        helper('jwt');
        $response = [
            'message' => 'Otentikasi Berhasil',
            'data' => $data,
            'access_token' => createJWT($email)
        ];

        return $this->respond($response);
    }
}
