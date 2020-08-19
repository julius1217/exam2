<?php
declare(strict_types=1);

namespace Tests\App\Functional\Http\Controllers\MailChimp;

use Tests\App\TestCases\MailChimp\MemberTestCase;

class MemberControllerTest extends MemberTestCase
{
    /**
     * Test application creates successfully Member and returns it back with id from MailChimp.
     *
     * @return void
     */
    public function testCreateMemberSuccessfully(): void
    {
        $this->post('/list/'.$list_id.'/Member', static::$MemberData);

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseOk();
        $this->seeJson(static::$MemberData);
        self::assertArrayHasKey('Member_id', $content);
        self::assertNotNull($content['Member_id']);

        $this->createdMemberIds[] = $content['Member_id']; // Store MailChimp Member id for cleaning purposes
    }

    /**
     * Test application returns error response with errors when Member validation fails.
     *
     * @return void
     */
    public function testCreateMemberValidationFailed(): void
    {
        $this->post('/mailchimp/Member');

        $content = \json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('errors', $content);
        self::assertEquals('Invalid data given', $content['message']);

        foreach (\array_keys(static::$MemberData) as $key) {
            if (\in_array($key, static::$notRequired, true)) {
                continue;
            }

            self::assertArrayHasKey($key, $content['errors']);
        }
    }

    /**
     * Test application returns error response when Member not found.
     *
     * @return void
     */
    public function testRemoveMemberNotFoundException(): void
    {
        $this->delete('/mailchimp/Members/invalid-Member-id');

        $this->assertMemberNotFoundResponse('invalid-Member-id');
    }

    /**
     * Test application returns empty successful response when removing existing Member.
     *
     * @return void
     */
    public function testRemoveMemberSuccessfully(): void
    {
        $this->post('/list/'.$list_id.'/Members', static::$MemberData);
        $Member = \json_decode($this->response->content(), true);

        $this->delete(\sprintf('/list/'.$list_id.'/Members', $Member['Member_id']));

        $this->assertResponseOk();
        self::assertEmpty(\json_decode($this->response->content(), true));
    }

    /**
     * Test application returns error response when Member not found.
     *
     * @return void
     */
    public function testShowMemberNotFoundException(): void
    {
        $this->get('/list/'.$list_id.'/Members/invalid-Member-id');

        $this->assertMemberNotFoundResponse('invalid-Member-id');
    }

    /**
     * Test application returns successful response with Member data when requesting existing Member.
     *
     * @return void
     */
    public function testShowMemberSuccessfully(): void
    {
        $Member = $this->createMember(static::$MemberData);

        $this->get(\sprintf('/list/'.$list_id.'/Members', $Member->getId()));
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();

        foreach (static::$MemberData as $key => $value) {
            self::assertArrayHasKey($key, $content);
            self::assertEquals($value, $content[$key]);
        }
    }

    /**
     * Test application returns error response when Member not found.
     *
     * @return void
     */
    public function testUpdateMemberNotFoundException(): void
    {
        $this->put('/list/'.$list_id.'/Members/invalid-Member-id');

        $this->assertMemberNotFoundResponse('invalid-Member-id');
    }

    /**
     * Test application returns successfully response when updating existing Member with updated values.
     *
     * @return void
     */
    public function testUpdateMemberSuccessfully(): void
    {
        $this->post('/list/'.$list_id.'/Members', static::$MemberData);
        $Member = \json_decode($this->response->content(), true);

        if (isset($Member['mail_chimp_id'])) {
            $this->createdMemberIds[] = $Member['mail_chimp_id']; // Store MailChimp Member id for cleaning purposes
        }

        $this->put(\sprintf('/list/'.$list_id.'/Members', $Member['Member_id']), ['permission_reminder' => 'updated']);
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseOk();

        foreach (\array_keys(static::$MemberData) as $key) {
            self::assertArrayHasKey($key, $content);
            self::assertEquals('updated', $content['permission_reminder']);
        }
    }

    /**
     * Test application returns error response with errors when Member validation fails.
     *
     * @return void
     */
    public function testUpdateMemberValidationFailed(): void
    {
        $Member = $this->createMember(static::$MemberData);

        $this->put(\sprintf('/lists/'.list_id.'/Members/%s', $Member->getId()), ['visibility' => 'invalid']);
        $content = \json_decode($this->response->content(), true);

        $this->assertResponseStatus(400);
        self::assertArrayHasKey('message', $content);
        self::assertArrayHasKey('errors', $content);
        self::assertArrayHasKey('visibility', $content['errors']);
        self::assertEquals('Invalid data given', $content['message']);
    }
}
