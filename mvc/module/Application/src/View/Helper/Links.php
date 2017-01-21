<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This view helper class displays links.
 */
class Links extends AbstractHelper
{
    /**
     * Array of items.
     * @var array
     */
    private $items = [];

    /**
     * Constructor.
     * @param array $items Array of items (optional).
     */
    public function __construct($items=[])
    {
        $this->items = $items;
    }

    /**
     * Sets the items.
     * @param array $items Items.
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * Renders the links.
     * @return string HTML code of the links.
     */
    public function render()
    {
        // Resulting HTML code will be stored in this var
        $result = '
        <section>
            <ul class="links">
                <li>
                    <a href="'.$this->view->url('family-gallery').'">
                        <h3>'.$this->view->translate('Family Gallery').'</h3>
                        <p>'.$this->view->translate('A small gallery of our family members').'</p>
                    </a>
                </li>
                <li>
                    <a href="'.$this->view->url('travels').'">
                        <h3>'.$this->view->translate('Travels').'</h3>
                        <p>'.$this->view->translate('Photos and stories about our travels').'</p>
                    </a>
                </li>
            </ul>
        </section>';

        return $result;

    }
}
