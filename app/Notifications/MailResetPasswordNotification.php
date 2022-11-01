<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
//custom
use Illuminate\Support\Facades\Lang;

class MailResetPasswordNotification extends Notification
{
	use Queueable;
	public $token;
	public $email;
	public $pageUrl;
	/**
	* Create a new notification instance.
	*
	* @return void
	*/
	public function __construct($token, $email)
	{
		$this->token = $token;
		$this->pageUrl = env('FRONT_URL') . '/reestablecer-contrasena-2/?token=' . $token . '&email=' . $email;
		$this->email=$email;
	}
		public function via($notifiable)
		{
			return ['mail'];
		}
	public function toMail($notifiable)
	{

		return (new MailMessage)
		->subject('Reestablecer contraseña')
		->view(
			'mails.forgot-password', ["pageUrl" => $this->pageUrl, 'email' => $this->email]
			);
	}
}
