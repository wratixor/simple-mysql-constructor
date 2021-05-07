<?php

#АВТОРИЗАЦИЯ Доделать
if (isset($_POST['core']['logbtn'])) {
    $login = null;
    $password = null;
    if (isset($_POST['core']['login']) and isset($_POST['core']['password'])) {
        $pre_login = preg_match('/^[a-zA-Z0-9а-яёА-ЯЁ]{4,64}$/u', $_POST['core']['login']);
        $pre_password = preg_match('/^.{4,64}$/u', $_POST['core']['password']);
        
        if ($pre_login === 1) {
            $login = $_POST['core']['login'];
        } elseif ($pre_login === 0) {
            $errors[] = 'Логин не соответствует стандарту!';
        } else {
            $errors[] = 'Неизвестная ошибка!';
        }
        if ($pre_password === 1) {
            $password = $_POST['core']['password'];
        } elseif ($pre_password === 0) {
            $errors[] = 'Пароль не соответствует стандарту!';
        } else {
            $errors[] = 'Неизвестная ошибка!';
        }
        
        if (is_null($login) or is_null($password)) {
            $page = new DefaultLoginPage();
            $page->add_Notices($errors);
            $page->show();
            die();
        }
        $status = 32;
        if ($_POST['core']['logbtn'] === WTA_AD_LOGIN) {
            $status = Paranoid::try_AD_Login($login, $password);
        }
        if ($_POST['core']['logbtn'] === WTA_WTA_LOGIN) {
            $status = Paranoid::try_Login($login, $password);
        }
        #TODO
        if ($status === 0) {
            $errors[] = 'Добро пожаловать!';
        } else {
            $errors[] = 'Неверный логин или пароль!';
            $page = new DefaultLoginPage();
            $page->add_Notices($errors);
            $page->show();
            die();
        }
        
    } else {
        $page = new DefaultLoginPage();
        $page->add_Notice(WTA_FATAL_ERROR);
        $page->show();
        die();
    }
}


    $paranoid = new Paranoid();
    
    if ($paranoid->need_Auth()) {
        $page = new DefaultLoginPage();
        $errors[] = 'Необходима авторизация!';
        $page->add_Notices($errors);
        $page->show();
        die();
    }
    if ($paranoid->need_more_Privilege(0)) {
        $page = new DefaultErrorPage('Недостаточно прав!');
        $errors[] = 'Недостаточно прав!';
        $page->add_Notices($errors);
        $page->show();
        die();
    }
    $page = new DefaultPage('Доброго времени суток!');
    $page->build_User($paranoid->get_UserInfo());



