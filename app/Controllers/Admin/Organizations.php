<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrganizationModel;
use App\Models\UserModel;

class Organizations extends BaseController
{
    protected $organizationModel;
    protected $userModel;

    public function __construct()
    {
        $this->organizationModel = new OrganizationModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['organizations'] = $this->organizationModel->findAll();
        return view('admin/organizations/index', $data);
    }

    public function create()
    {
        return view('admin/organizations/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'acronym' => 'permit_empty|max_length[50]',
            'description' => 'permit_empty',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Create organization
        $orgData = [
            'name' => $this->request->getPost('name'),
            'acronym' => $this->request->getPost('acronym'),
            'description' => $this->request->getPost('description'),
            'status' => 'active'
        ];

        $orgId = $this->organizationModel->insert($orgData);

        if ($orgId) {
            // Create user account
            $userData = [
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'role' => 'organization',
                'organization_id' => $orgId,
                'is_active' => 1
            ];

            $this->userModel->insert($userData);

            return redirect()->to('/admin/organizations')->with('success', 'Organization created successfully');
        }

        return redirect()->back()->with('error', 'Failed to create organization');
    }

    public function edit($id)
    {
        $data['organization'] = $this->organizationModel->find($id);
        
        if (!$data['organization']) {
            return redirect()->to('/admin/organizations')->with('error', 'Organization not found');
        }

        $data['user'] = $this->userModel->where('organization_id', $id)->first();

        return view('admin/organizations/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]',
            'acronym' => 'permit_empty|max_length[50]',
            'description' => 'permit_empty',
            'status' => 'required|in_list[active,inactive,suspended]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $orgData = [
            'name' => $this->request->getPost('name'),
            'acronym' => $this->request->getPost('acronym'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->organizationModel->update($id, $orgData)) {
            // Update user if password is provided
            $newPassword = $this->request->getPost('new_password');
            if (!empty($newPassword)) {
                $user = $this->userModel->where('organization_id', $id)->first();
                if ($user) {
                    $this->userModel->update($user['id'], ['password' => $newPassword]);
                }
            }

            return redirect()->to('/admin/organizations')->with('success', 'Organization updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update organization');
    }

    public function delete($id)
    {
        if ($this->organizationModel->delete($id)) {
            return redirect()->to('/admin/organizations')->with('success', 'Organization deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete organization');
    }

    public function view($id)
    {
        $data['organization'] = $this->organizationModel->getOrganizationWithStats($id);
        
        if (!$data['organization']) {
            return redirect()->to('/admin/organizations')->with('error', 'Organization not found');
        }

        return view('admin/organizations/view', $data);
    }
}