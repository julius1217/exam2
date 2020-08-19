<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Str;

/**
 * @ORM\Entity()
 */
class MailChimpMember extends MailChimpEntity
{
    /**
     * @ORM\Column(name="campaign_defaults", type="array")
     *
     * @var array
     */
    private $campaignDefaults;

    /**
     * @ORM\Column(name="contact", type="array")
     *
     * @var array
     */
    private $contact;

    /**
     * @ORM\Column(name="merge_fields", type="array")
     *
     * @var array
     */
    private $merge_fields;

    /**
     * @ORM\Column(name="interests", type="array")
     *
     * @var array
     */
    private $interests;

    

    /**
     * @ORM\Column(name="email_type_option", type="boolean")
     *
     * @var bool
     */
    private $emailTypeOption;

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $listId;

    /**
     * @ORM\Column(name="mail_chimp_id", type="string", nullable=true)
     *
     * @var string
     */
    private $mailChimpId;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="notify_on_subscribe", type="string", nullable=true)
     *
     * @var string
     */
    private $notifyOnSubscribe;

    /**
     * @ORM\Column(name="notify_on_unsubscribe", type="string", nullable=true)
     *
     * @var string
     */
    private $notifyOnUnsubscribe;

    /**
     * @ORM\Column(name="permission_reminder", type="string")
     *
     * @var string
     */
    private $permissionReminder;

    /**
     * @ORM\Column(name="use_archive_bar", type="boolean", nullable=true)
     *
     * @var bool
     */
    private $useArchiveBar;

    /**
     * @ORM\Column(name="visibility", type="string", nullable=true)
     *
     * @var string
     */
    private $visibility;

    /**
     * @ORM\Column(name="email_address", type="string")
     * 
     * @var string
     */
    private $email_address;

    /**
     * @ORM\unique_email_id()
     * @ORM\Column(name="unique_email_id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * 
     * @var string
     */
    private $unique_email_id;

     /**
     * @ORM\web_id()
     * @ORM\Column(name="web_id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * 
     * @var string
     */
    private $web_id;

    /**
     * Get unique_email_id.
     *
     * @return null|string
     */
    public function getunique_email_id(): ?string
    {
        return $this->unique_email_id;
    }

      /**
     * Get web_id.
     *
     * @return null|string
     */
    public function getweb_id(): ?string
    {
        return $this->web_id;
    }

     /**
     * Get id.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->listId;
    }


    /**
     * Get mailchimp id of the list.
     *
     * @return null|string
     */
    public function getMailChimpId(): ?string
    {
        return $this->mailChimpId;
    }

    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'campaign_defaults' => 'required|array',
            'campaign_defaults.from_name' => 'required|string',
            'campaign_defaults.from_email' => 'required|string',
            'campaign_defaults.subject' => 'required|string',
            'campaign_defaults.language' => 'required|string',
            'contact' => 'required|array',
            'contact.company' => 'required|string',
            'contact.address1' => 'required|string',
            'contact.address2' => 'nullable|string',
            'contact.city' => 'required|string',
            'contact.state' => 'required|string',
            'contact.zip' => 'required|string',
            'contact.country' => 'required|string|size:2',
            'contact.phone' => 'nullable|string',
            'email_type_option' => 'required|boolean',
            'name' => 'required|string',
            'notify_on_subscribe' => 'nullable|email',
            'notify_on_unsubscribe' => 'nullable|email',
            'mailchimp_id' => 'nullable|string',
            'permission_reminder' => 'required|string',
            'use_archive_bar' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:pub,prv'
        ];
    }

    /**
     * Set campaign defaults.
     *
     * @param array $campaignDefaults
     *
     * @return MailChimpMember
     */
    public function setCampaignDefaults(array $campaignDefaults): MailChimpMember
    {
        $this->campaignDefaults = $campaignDefaults;

        return $this;
    }

    /**
     * Set contact.
     *
     * @param array $contact
     *
     * @return MailChimpMember
     */
    public function setContact(array $contact): MailChimpMember
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Set merge_fields.
     *
     * @param array $merge_fields
     *
     * @return MailChimpMember
     */
    public function setmerge_fields(array $merge_fields): MailChimpMember
    {
        $this->merge_fields = $merge_fields;

        return $this;
    }

    /**
     * Set interests.
     *
     * @param array $interests
     *
     * @return MailChimpMember
     */
    public function setinterests(array $interests): MailChimpMember
    {
        $this->interests = $interests;

        return $this;
    }

    
    
    /**
     * Set email type option.
     *
     * @param bool $emailTypeOption
     *
     * @return MailChimpMember
     */
    public function setEmailTypeOption(bool $emailTypeOption): MailChimpMember
    {
        $this->emailTypeOption = $emailTypeOption;

        return $this;
    }

    /**
     * Set mailchimp id of the list.
     *
     * @param string $mailChimpId
     *
     * @return \App\Database\Entities\MailChimp\MailChimpMember
     */
    public function setMailChimpId(string $mailChimpId): MailChimpMember
    {
        $this->mailChimpId = $mailChimpId;

        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return MailChimpMember
     */
    public function setName(string $name): MailChimpMember
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set email_address.
     *
     * @param string $email_address
     *
     * @return MailChimpMember
     */
    public function setemail_address(string $email_address): MailChimpMember
    {
        $this->email_address = $email_address;

        return $this;
    }
    
    /**
     * Set notify on subscribe.
     *
     * @param string $notifyOnSubscribe
     *
     * @return MailChimpMember
     */
    public function setNotifyOnSubscribe(string $notifyOnSubscribe): MailChimpMember
    {
        $this->notifyOnSubscribe = $notifyOnSubscribe;

        return $this;
    }

    /**
     * Set notify on unsubscribe.
     *
     * @param string $notifyOnUnsubscribe
     *
     * @return MailChimpMember
     */
    public function setNotifyOnUnsubscribe(string $notifyOnUnsubscribe): MailChimpMember
    {
        $this->notifyOnUnsubscribe = $notifyOnUnsubscribe;

        return $this;
    }

    /**
     * Set permission reminder.
     *
     * @param string $permissionReminder
     *
     * @return MailChimpMember
     */
    public function setPermissionReminder(string $permissionReminder): MailChimpMember
    {
        $this->permissionReminder = $permissionReminder;

        return $this;
    }

    /**
     * Set use archive bar.
     *
     * @param bool $useArchiveBar
     *
     * @return MailChimpMember
     */
    public function setUseArchiveBar(bool $useArchiveBar): MailChimpMember
    {
        $this->useArchiveBar = $useArchiveBar;

        return $this;
    }

    /**
     * Set visibility.
     *
     * @param string $visibility
     *
     * @return MailChimpMember
     */
    public function setVisibility(string $visibility): MailChimpMember
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $str = new Str();

        foreach (\get_object_vars($this) as $property => $value) {
            $array[$str->snake($property)] = $value;
        }

        return $array;
    }


}
