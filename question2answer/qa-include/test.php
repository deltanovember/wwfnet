<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
//require_once 'qa-base.php';
			require_once 'qa-class.phpmailer.php';

			$mailer=new PHPMailer();
			$mailer->CharSet='utf-8';

			$mailer->From='admin@wordswithfriends.net';
			$mailer->Sender='admin@wordswithfriends.net';
			$mailer->FromName='Don Nguyen';
			$mailer->AddAddress('don@unswrc.com', 'Don Nguyen');
			$mailer->Subject='subject';
			$mailer->Body='body';

			if (1)
				$mailer->IsHTML(true);

			if ( $mailer->Send()) {
                            print "success";
                        }
                        else {
                            print "fail";
                        }
?>
