<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Http\Response;
use Exception;

/**
 * Class AppController
 * @package App\Controller
 */
class AppController extends Controller
{
    const PAGINATION_MAX_LIMIT = 10000;
    /**
     * @var $authUser
     */
    protected $authUser;

    /**
     * @throws Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Methods');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                    'scope' => [
                        'active' => 'yes'
                    ]
                ],
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            // If unauthorized, return them to page they were just on
            'unauthorizedRedirect' => $this->referer()
        ]);

        $this->Auth->allow([
            'login',
            'logout'
        ]);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    /**
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->User()) {
            $this->loadModel('Users');
            $this->authUser = $this->Users->get($this->Auth->User('id'), [
                'contain' => [
                    'Roles'
                ]
            ]);
            $this->set('authUser', $this->authUser);
        }
    }

    /**
     * @param $package
     * @return Response
     */
    protected function _throw($package)
    {
        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody(json_encode($package));
    }

}
