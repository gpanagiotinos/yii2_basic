<?php 
	namespace app\models;

	use Yii;
	use yii\base\Model;
	use yii\db\ActiveRecord;

	/**
	* Model to create new user to db
	*/
	class NewuserForm extends ActiveRecord
	{
		public $username;
		public $password;
		public $email;

		public static function tableName()
		{
			return 'User';
		}

		public function rules()
		{
			return [
			[['username', 'password', 'email'], 'required'],
			['email', 'email'],
			];
		}
	}
 ?>