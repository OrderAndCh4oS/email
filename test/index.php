<?php
    require_once "../vendor/autoload.php";
    require_once "../src/Email/Email.php";

    $name = "Sean Cooper";
    $body = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sodales magna sagittis lacinia blandit. Vestibulum ut tellus at ligula consequat mattis vel vel ante. Vestibulum vitae facilisis turpis. Etiam tincidunt a nunc at commodo. Cras aliquet sollicitudin eros. Donec convallis scelerisque consectetur. Nam consequat varius venenatis. Donec malesuada nec risus quis ornare.

Vestibulum rhoncus, ex ullamcorper pharetra egestas, arcu odio placerat ipsum, ut iaculis libero arcu in mauris. Donec non vehicula elit. Sed eu dolor vitae mauris convallis aliquam id vel felis. Morbi commodo et est ac tempor. Praesent porta lorem faucibus gravida ullamcorper. Donec vitae ultricies orci. Curabitur eu nunc purus. Donec malesuada tincidunt pretium. Sed viverra quis purus nec gravida. Proin a placerat justo. Vestibulum mollis odio et diam finibus imperdiet. Donec quis lorem tincidunt, aliquam lorem at, iaculis tellus. Aliquam in turpis non nulla sagittis cursus. Pellentesque eget eleifend eros. Vivamus eget libero quis erat tincidunt posuere.";

    $email = new Sarcoma\Email\Email("'Palatino Linotype', 'Book Antiqua', Palatino, serif", 18, 1.4);

    $errors = $email->check_required_fields(array(
        'Name' => $name,
        'Body' => $body
    ));

    if (empty($errors)) {
        $h2_style = array('font-size' => $email->modularScale(1));
        $email->setBodyColor('#e1e9ee');
        $email->setTableColor('#f1f9ff');
        $email->setTag('Email Title', 'h1', array('font-size' => $email->modularScale(2)));
        $email->setTag('Name', 'h2', $h2_style);
        $email->setTag($name, 'p');
        $email->setTag('Message', 'h2', $h2_style);
        $email->setTextArea($body, 'p', array(), 60);
        $email->setLink('three&me','https://www.threeandme.co.uk', 'p');
        echo $email->getMessage();
    } else {
        foreach ($errors as $error) {
            echo $error;
        }
    }

