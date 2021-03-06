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
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
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
		$this->loadComponent('Auth', [
		 'authenticate' => [
                'Form' => [
				'fields' => [
                        'usernames' => 'usernames',
                        'password' => 'password'
                    ],
					'scope' => ['is_deleted' => false],
                    'userModel' => 'Users'
                ]
            ],
			'loginAction' => ['controller' => 'Users', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'News', 'action' => 'index'],
			'unauthorizedRedirect' => $this->referer(),
        ]);
		
		 $this->userId = $this->Auth->user('id');
        // Time::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any mutable DateTime
        // FrozenTime::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any immutable DateTime
        // Date::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any mutable Date
        // FrozenDate::setJsonEncodeFormat('yyyy-MM-dd HH:mm:ss');  // For any immutable Date

        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }
	public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        
        $user_id = $this->userId;
		$this->set(compact('user_id'));
       
    }
	protected function _getRandomString($length = 10, $validCharacters = null)
    {
        if($validCharacters == '')
        {
            $validCharacters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        }
        
        $validCharactersCount = strlen($validCharacters);
        
        $string = '';
        for($i=0; $i<$length; $i++)
        {
            $string .= $validCharacters[mt_rand(0, $validCharactersCount-1)];
        }
        
        return $string;
    }
}
