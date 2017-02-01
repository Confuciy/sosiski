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

    private $userManager;

    /**
     * Constructor.
     * @param array $items Array of items (optional).
     */
    public function __construct($items=[], $userManager)
    {
        $this->items = $items;
        $this->userManager = $userManager;
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
                </li>';

                if (isset($_SESSION['Zend_Auth']->session) and $_SESSION['Zend_Auth']->session != '') {
                    // Find a user with such Email.
                    $user = $this->userManager->getUserByEmail($_SESSION['Zend_Auth']->session);
                    $user_roles = $this->userManager->getUserRolesIds($user['id']);

                    // If user is Administrator
                    if (in_array(4, $user_roles)) {
                        $result .= '
                        <li>
                            <h3>'.$this->view->translate('Administration').'</h3>
                            <a href="'.$this->view->url('users').'"><p>'.$this->view->translate('Administration of users').'</p></a>
                            <a href="'.$this->view->url('travels_admin').'"><p>'.$this->view->translate('Administration of travels').'</p></a>
                        </li>';
                    }
                }

            $result .= '
            </ul>
        </section>';
//        if (in_array(4, $user_roles)) {
//            $result .= '
//            <section style="margin-left: 5px; font-size: 0.8em;">
//                <a href="' . $this->view->url('users') . '">' . $this->view->translate('Administration of users') . '</a>
//                <br />
//                <a href="' . $this->view->url('travels_admin') . '">' . $this->view->translate('Administration of travels') . '</a>
//            </section>';
//        }

        return $result;

    }
}
