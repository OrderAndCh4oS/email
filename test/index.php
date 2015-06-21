<?php
    require_once "../src/Email/Email.php";

    $name = "Sean";
    $body = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sodales magna sagittis lacinia blandit. Vestibulum ut tellus at ligula consequat mattis vel vel ante. Vestibulum vitae facilisis turpis. Etiam tincidunt a nunc at commodo. Cras aliquet sollicitudin eros. Donec convallis scelerisque consectetur. Nam consequat varius venenatis. Donec malesuada nec risus quis ornare.

Vestibulum rhoncus, ex ullamcorper pharetra egestas, arcu odio placerat ipsum, ut iaculis libero arcu in mauris. Donec non vehicula elit. Sed eu dolor vitae mauris convallis aliquam id vel felis. Morbi commodo et est ac tempor. Praesent porta lorem faucibus gravida ullamcorper. Donec vitae ultricies orci. Curabitur eu nunc purus. Donec malesuada tincidunt pretium. Sed viverra quis purus nec gravida. Proin a placerat justo. Vestibulum mollis odio et diam finibus imperdiet. Donec quis lorem tincidunt, aliquam lorem at, iaculis tellus. Aliquam in turpis non nulla sagittis cursus. Pellentesque eget eleifend eros. Vivamus eget libero quis erat tincidunt posuere.";

    $email = new Sarcoma\Email\Email();

    $email->setEmailTitle('Test Email');
    $email->setH1Style('#333', '24px', "'Palatino Linotype', 'Book Antiqua', Palatino, serif");
    $email->setH2Style('#333', '18px', "'Palatino Linotype', 'Book Antiqua', Palatino, serif");
    $email->setPStyle('#999', '13px', "'Times New Roman', Times, serif");

        $email->setText(array(
            'Name' => $name
        ));
        $email->setTextArea(array(
           'Body' => $body
        ));

    $errors = $email->check_required_fields(array(
        'Name' => $name,
        'Body' => $body
    ));

    if(empty($errors)) {
        $message = $email->buildMessage();
        echo $message;
    } else {
        foreach ($errors as $error) {
            echo $error;
        }
    }

