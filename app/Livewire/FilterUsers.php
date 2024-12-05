<?php

namespace App\Livewire;

use Livewire\Component;
use App\Enums\Departments;
use App\Enums\Role;

class FilterUsers extends Component
{
    public $term;
    public $department;
    public $role;

    public function readFormInput()
    {
        $this->dispatch('filter', $this->term, $this->department, $this->role);
    }

    public function render()
    {
        $departments = Departments::cases();
        $role = Role::cases();


        return view('livewire.filter-users', ['userDepartments' => $departments, 'userRol' => $role]);
    }

}
