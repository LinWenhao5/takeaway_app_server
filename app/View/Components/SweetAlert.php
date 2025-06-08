<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SweetAlert extends Component
{
    public string $formId;
    public string $title;
    public string $text;
    public string $confirmButtonText;
    public string $successMessage;
    public string $errorMessage;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $formId,
        string $title = 'Are you sure?',
        string $text = "You won't be able to revert this!",
        string $confirmButtonText = 'Yes, confirm!',
        string $successMessage = 'Action completed successfully!',
        string $errorMessage = 'An error occurred.'
    ) {
        $this->formId = $formId;
        $this->title = $title;
        $this->text = $text;
        $this->confirmButtonText = $confirmButtonText;
        $this->successMessage = $successMessage;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sweet-alert');
    }
}