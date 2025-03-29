<?php

namespace App\Mail;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShopVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $shop;
    public $user;

    public function __construct(Shop $shop, User $user)
    {
        $this->shop = $shop;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ยืนยันบัญชีร้านค้ากับ SeeQ'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.shop.verify',
            with: [
                'shop' => $this->shop,
                'user' => $this->user,
                'verificationLink' => env("EMAIL_CALLBACK_URL") . "/api/auth/emails/" . $this->user->id . "/" . sha1($this->user->email) . "/verify",
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
