<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\Roles;
use app\models\Info;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login', 'update', 'users'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],

                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['users'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            $id = Yii::$app->user->getId();
                            $admin_id = User::find()
                            ->where(['id'=>$id])->one();

                            if($admin_id->role_id < 3){
                                return true; 
            
                                
                            }else{
                                return false;
                            }
                            
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           // print_r(Yii::$app->user->getId());
           // die;
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    //Hello World!!!
    public function actionSay($message = 'Hello'){
        return $this->render('say', ['message' => $message ]);
    }

    public function actionSignup(){

        $user = new User();
        $info = new Info();

        if ($user->load(Yii::$app->request->post()) && $info->load(Yii::$app->request->post()) && $user->validate()) {
            //validated model for use
            //Insert in db
            //hashed password
            $hash = Yii::$app->getSecurity()->generatePasswordHash($user->password);
            $user->password = $hash;
            //generate auth_key
            $user->auth_key = \Yii::$app->security->generateRandomString();
            //insert model user in db
            
            //TO DO Foreign key for modefied by and created by

            $user->save();


            //retrieve last user id !!! MAYBE WE CAN USER getId();
            $user_info = User::find()
            ->select(['id'])
            ->where(['username' => $user->username])->one();
            //assign last user id to info table
           $info->user_id = $user_info->id;

           //add created by id
           //insert info table
           $info->save();

           //redirect to login form
           $model = new LoginForm();
           return $this->redirect('index.php?r=site/login');
            
           /*//View data
           $current_users = User::find()
           ->select(['username', 'email'])->all();
            return $this->render('user-confirm', ['current_users' => $current_users ]);
            */
        }else{
            //error with user validation
            $roles = Roles::find()
            ->select(['rolename', 'role_id'])
            ->indexBy('role_id')
            ->column();
            return $this->render('signup', ['user' => $user, 'roles' => $roles, 'info'=>$info ]);
        }
    }
    public function actionUpdate($id){
        /*

        GRID


        $query = User::find();
        $updated_user = new ActiveDataProvider([
            'query' => $query,
        ]);
         $query->where(['id'=> $id]);
        return $this->render('update', compact('updated_user'));
        */
        $user_updated = User::find($id)->one();
        $info_updated = Info::find($user_updated->id)->one();
        $role_updated = Roles::find()
            ->select(['rolename', 'role_id'])
            ->indexBy('role_id')
            ->column();
        if ($user_updated->load(Yii::$app->request->post()) && $info_updated->load(Yii::$app->request->post())){
            $isValid = $user_updated->validate();
            $isValid = $info_updated->validate() && $isValid;
            if ($isValid) {
                $user_updated->save(false);
                $info_updated->save(false);
                return $this->goHome();;

            }
        }
        return $this->render('update', [
            'user_updated' => $user_updated,
            'info_updated' => $info_updated,
            'role_updated'=> $role_updated
        ]);


    }

    public function actionUser(){
            $user_id = Yii::$app->user->getId();
            $current_user = User::find($user_id)
            ->select(['id', 'username', 'email'])->one();
            return $this->render('user', ['current_user' => $current_user ]);
        }

    public function actionUsers(){
        $query = User::find();
        $grid_users = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('grid_users', compact('grid_users'));
    }
}
