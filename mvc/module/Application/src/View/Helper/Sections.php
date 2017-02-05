<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Sections extends AbstractHelper
{
    private $path = '';

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->path = dirname(__FILE__).'/../../../../../public/scripts/instagram';
    }

    public function instagramSection()
    {
        $section_path = $this->path.'/instagram_section_'.$_SESSION['locale'].'.txt';

        $handle = fopen($section_path, "r");
        $section_html = fread($handle, filesize($section_path));
        fclose($handle);

        return $section_html;
    }
}
