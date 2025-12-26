<?php

namespace App\Controllers\Organization;

use App\Controllers\BaseController;
use App\Models\CommitmentFormModel;
use App\Models\OrganizationModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class CommitmentForm extends BaseController
{
    protected $commitmentModel;
    protected $organizationModel;

    public function __construct()
    {
        $this->commitmentModel = new CommitmentFormModel();
        $this->organizationModel = new OrganizationModel();
    }

    public function index()
    {
        $organizationId = session()->get('organization_id');
        $forms = $this->commitmentModel->getByOrganization($organizationId);
        $selectedId = $this->request->getGet('id');

        $selectedForm = null;
        if (!empty($selectedId)) {
            $candidate = $this->commitmentModel->find($selectedId);
            if ($candidate && ($candidate['organization_id'] ?? null) == $organizationId) {
                $selectedForm = $candidate;
            }
        }

        if (empty($selectedForm) && !empty($forms)) {
            $selectedForm = $forms[0];
        }

        $data['forms'] = $forms;
        $data['form'] = $selectedForm;
        $data['organization'] = $this->organizationModel->find($organizationId);

        return view('organization/forms/commitment_form', $data);
    }

    public function create()
    {
        $organizationId = session()->get('organization_id');
        $data['organization'] = $this->organizationModel->find($organizationId);
        
        return view('organization/forms/commitment_form', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $payload = $this->request->getJSON(true);
        if (!is_array($payload) || empty($payload)) {
            $payload = $this->request->getPost();
        }

        $rules = [
            'officer_name' => 'required|min_length[3]',
            'position' => 'required',
            'organization_name' => 'required',
            'academic_year' => 'required',
            'signed_date' => 'required|valid_date',
        ];

        if (!$validation->setRules($rules)->run($payload)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
                'csrf' => csrf_hash()
            ]);
        }

        $data = [
            'organization_id' => session()->get('organization_id'),
            'officer_name' => $payload['officer_name'] ?? null,
            'position' => $payload['position'] ?? null,
            'organization_name' => $payload['organization_name'] ?? null,
            'academic_year' => $payload['academic_year'] ?? null,
            'signed_date' => $payload['signed_date'] ?? null,
            'status' => 'draft'
        ];

        $id = $this->commitmentModel->insert($data);

        if ($id) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Commitment form saved successfully',
                'id' => $id,
                'csrf' => csrf_hash()
            ]);
        }

        $dbError = $this->commitmentModel->db->error();

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to save commitment form',
            'errors' => $this->commitmentModel->errors(),
            'db_error' => $dbError['message'] ?? null,
            'csrf' => csrf_hash()
        ]);
    }

    public function update($id)
    {
        $form = $this->commitmentModel->find($id);
        
        if (!$form || $form['organization_id'] != session()->get('organization_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Form not found',
                'csrf' => csrf_hash()
            ]);
        }

        $payload = $this->request->getJSON(true);
        if (!is_array($payload) || empty($payload)) {
            $payload = $this->request->getPost();
        }

        $validation = \Config\Services::validation();
        $rules = [
            'officer_name' => 'required|min_length[3]',
            'position' => 'required',
            'organization_name' => 'required',
            'academic_year' => 'required',
            'signed_date' => 'required|valid_date',
        ];

        if (!$validation->setRules($rules)->run($payload)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors(),
                'csrf' => csrf_hash()
            ]);
        }

        $data = [
            'officer_name' => $payload['officer_name'] ?? null,
            'position' => $payload['position'] ?? null,
            'organization_name' => $payload['organization_name'] ?? null,
            'academic_year' => $payload['academic_year'] ?? null,
            'signed_date' => $payload['signed_date'] ?? null,
        ];

        if ($this->commitmentModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Commitment form updated successfully',
                'csrf' => csrf_hash()
            ]);
        }

        $dbError = $this->commitmentModel->db->error();

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to update commitment form',
            'errors' => $this->commitmentModel->errors(),
            'db_error' => $dbError['message'] ?? null,
            'csrf' => csrf_hash()
        ]);
    }

    public function download($id)
    {
        $form = $this->commitmentModel->find($id);
        
        if (!$form || $form['organization_id'] != session()->get('organization_id')) {
            return redirect()->back()->with('error', 'Form not found');
        }

        // Generate PDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        $html = view('pdf/commitment_form', ['form' => $form]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream('commitment_form_' . $id . '.pdf', ['Attachment' => true]);
    }

    public function print($id)
    {
        $form = $this->commitmentModel->find($id);
        
        if (!$form || $form['organization_id'] != session()->get('organization_id')) {
            return redirect()->back()->with('error', 'Form not found');
        }

        $data['form'] = $form;
        return view('pdf/commitment_form', $data);
    }
}