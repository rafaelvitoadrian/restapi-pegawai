<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;

class Pegawai extends BaseController
{
    use ResponseTrait;
    protected $pegawaiModel;
    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data = $this->pegawaiModel->orderBy('nama','asc')->findAll();
        return $this->respond($data,200);
    }

    public function create()
    {
        // $data = [
        //     'nama' => $this->request->getVar('nama'),
        //     'email' => $this->request->getVar('email')
        // ];

        $data = $this->request->getPost();
        if (!$this->pegawaiModel->save($data)) {
            return $this->fail($this->pegawaiModel->errors());
        }
        
        $this->pegawaiModel->save($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messsages' => [
                'success' => 'Berhasil Memasukan data pegawi'
            ]
        ];

        return $this->respond($response);
    }

    public function show($id = '')
    {
        $data = $this->pegawaiModel->where('id', $id)->findAll();
        if ($data) {
            return $this->respond($data,200);
        }else {
            return $this->failNotFound('Data Tidak ditemukan');
        }
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $data['id'] = $id;

        $isExist = $this->pegawaiModel->where('id', $id)->findAll();
        if (!$isExist) {
            return $this->failNotFound('Data Tidak ditemukan');
        }

        // validation
        if (!$this->pegawaiModel->save($data)) {
            return $this->fail($this->pegawaiModel->errors());
        }

        $response = [
            'status' => 201,
            'error' => null,
            'messsages' => [
                'success' => 'Berhasil mengupadate data pegawai'
            ]
        ];

        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $data = $this->pegawaiModel->where('id', $id)->findAll();
        if ($data) {
            $this->pegawaiModel->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'data berhasil dihapus'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('Data tidak ditemukan dan gagal dihapus');
        }
    }
}
