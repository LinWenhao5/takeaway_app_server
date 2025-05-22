<?php
namespace App\View\Components;

use Illuminate\View\Component;

class MediaSelector extends Component
{
    public $media;
    public $label;
    public $name;
    public $selected;

    /**
     * Create a new component instance.
     *
     * @param array $media
     * @param string $label
     * @param string $name
     * @param array $selected
     */
    public function __construct($media, $label = 'Select Media', $name = 'media', $selected = [])
    {
        $this->media = $media;
        $this->label = $label;
        $this->name = $name;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.media-selector');
    }
}