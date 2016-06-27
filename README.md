#UTF-Mailer
Easy PHP class for sending emails. Avoids all the spam filters. 
Uses templates. 
Easy to use and does not require any dependencies

##Version 2

##Usage

        $template = (new Template('/path/to/tempaltes/'))->render('contacts', $attributes);

		UTFMail::to('email@email.com')
			->from('My Name', 'my@email.com')
			->title('My message')
			->body($template)
			->send();
