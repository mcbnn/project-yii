<?php

namespace app\controllers;

use app\models\RegistrationForm;
use yii\web\NotFoundHttpException;
use dektrium\user\controllers\RegistrationController;
use Yii;

class _RegistrationController extends RegistrationController{

    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise
     * redirects to home page.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException();
        }

        /** @var RegistrationForm $model */
        $model = \Yii::createObject(RegistrationForm::className());

        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_REGISTER, $event);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            $this->trigger(self::EVENT_AFTER_REGISTER, $event);

            return $this->render('/message', [
                'title'  => \Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->render('//site/registration', [
            'model'  => $model,
            'module' => $this->module
        ]);
    }

    /**
     * @return array
     */
    public function getAllArrayRoles()
    {
        $rolesObj = Yii::$app->authManager->getRoles();

        if(empty($rolesObj))return [0 => 'admin', 1 => 'author'];
        return array_keys($rolesObj);
    }

}