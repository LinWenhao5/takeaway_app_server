<?php
namespace App\View\Components;

use Illuminate\View\Component;

class MediaSelector extends Component
{
    public $media;
    public $label;
    public $name;

    /**
     * Create a new component instance.
     *
     * @param array $media
     * @param string $label
     * @param string $name
     */
    public function __construct($media, $label = 'Select Media', $name = 'media')
    {
        $this->media = $media;
        $this->label = $label;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.media-selector');
    }
}