<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */

class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');


        // Load models
        $this->loadModel("Users");
        $this->loadModel("Stores");
        $this->loadModel("Messages");
        $this->loadModel("Departments");
        $this->loadModel("Dailytasks");
        $this->loadModel("Tasks");
        $this->loadModel("Tests");

        // See it: http://weekend-it.blog.jp/archives/41470870.html
        // Access to session in all pages use $this->Session
        $this->Session = $this->request->getSession();


        //Cookie : https://qiita.com/trewa-nek9585/items/67743cc869be16921046
    }

    public function beforeFilter(Event $event) {
        //error_reporting(E_ALL & ~E_DEPRECATED);

        $this->Cookie->config([
            'expires' => '+365 days'
        ]);
    }


}
