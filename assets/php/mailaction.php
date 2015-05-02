<?php

	$from = $_POST['email']

	$mail = new \mail();
	$mail->addAddress('m_harreither@hotmail.com');
	$mail->subject("Kontaktanfrage:$_POST['name']");
	$mail->body("Nachricht: $_POST['text']".$from);
	$mail->send();
?>