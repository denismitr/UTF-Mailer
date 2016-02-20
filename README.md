#UTF-Mailer#
Easy PHP class for sending emails

Easy to use and does not require any dependencies

##Version 3##

##Usage##

		UTFMail::to('email@email.com')
			->from('My Name', 'my@email.com')
			->title('My message')
			->body('My message body')
			->send();
