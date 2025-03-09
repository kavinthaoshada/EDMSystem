<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;

class EmployeeDocuments extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'document_name';
    public $sortDirection = 'asc';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $documents = Document::where('employee_id', Auth::id())
            ->where(function ($query) {
                $query->where('document_name', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($q) {
                          $q->where('category_name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(5);

        return view('livewire.employee-documents', compact('documents'));
    }
}
