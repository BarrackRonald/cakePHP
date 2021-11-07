<?php

declare(strict_types=1);

namespace App\Controller\Component;
use Cake\Auth\DefaultPasswordHasher;
use PhpParser\Node\Stmt\TryCatch;

/**
 * Data component
 */
class ValidateComponent extends CommonComponent
{
	/**
	 * Default configuration.
	 *
	 * @var array
	 */
	protected $_defaultConfig = [];
	public function initialize(array $config): void
	{
		$this->loadModel(['Users']);
		$this->loadModel('Roles');
		$this->loadModel('Products');
		$this->loadModel('Images');
		$this->loadModel('Categories');
		$this->loadModel('Orders');
		$this->loadModel('Orderdetails');
	}

	//Validate Phonenumber
    public function validatePhoneNumber($phoneNumber)
    {
        $checkphoneNumber = preg_match('/^(0)([0-9]){9}$/', $phoneNumber);

        if($phoneNumber == null || $phoneNumber == '')
        {
            return [
				'result' => 'invalid',
				'message' => ERROR_NULL_PHONE_NUMBER,
			];
        }else if($checkphoneNumber == 0){
            return [
				'result' => 'invalid',
				'message' => ERROR_INVALID_PHONE_NUMBER,
			];
        }else{
            return [
				'result' => 'success',
                'message' => '',
			];
        }
    }

    //Validate Username
    public function validateUsername($username){


        if($username == null || $username == ''){
            return [
                'result' => 'invalid',
                'message' => ERROR_NULL_USERNAME,
            ];
        }else{
            return [
                'result' => 'success',
                'message' => '',
            ];
        }
    }

    //Validate Address
    public function validateAddress($address){

        if($address == null || $address == ''){
            return [
                'result' => 'invalid',
                'message' => ERROR_NULL_ADDRESS,
            ];
        }else{
            return [
                'result' => 'success',
                'message' => '',
            ];
        }
    }



}
