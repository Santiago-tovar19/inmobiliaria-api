<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
//custom
use Illuminate\Support\Facades\Lang;

class MailCreateAccount extends Notification
{
	use Queueable;
	public $token;
	public $email;
	public $pageUrl;
	public $rol;
	/**
	* Create a new notification instance.
	*
	* @return void
	*/
	public function __construct($token, $email, $rol)
	{
		$this->token = $token;
		$this->pageUrl = env('FRONT_URL') . '/crear-cuenta/?token=' . $token . '&email=' . $email;
		$this->email=$email;
		$this->rol=$rol;
	}
		public function via($notifiable)
		{
			return ['mail'];
		}
	public function toMail($notifiable)
	{

		return (new MailMessage)
		->subject('Registro | Inmobiliaria')
		->view(
			'mails.registro', ["pageUrl" => $this->pageUrl, 'email' => $this->email, 'rol' => $this->rol]
			);
	}
}
