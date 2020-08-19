<?php
declare(strict_types=1);

namespace App\Http\Controllers\MailChimp;

use App\Database\Entities\MailChimp\MailChimpMember;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mailchimp\Mailchimp;

class MemberController extends Controller
{
    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * MemberController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Mailchimp\Mailchimp $mailchimp
     */
    public function __construct(EntityManagerInterface $entityManager, Mailchimp $mailchimp)
    {
        parent::__construct($entityManager);

        $this->mailChimp = $mailchimp;
    }

    /**
     * Create MailChimp Member.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // Instantiate entity
        $Member = new MailChimpMember($request->all());
        // Validate entity
        $validator = $this->getValidationFactory()->make($Member->toMailChimpArray(), $Member->getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                'message' => 'Invalid data given',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            // Save Member into db
            $this->saveEntity($Member);
            // Save Member into MailChimp
            $response = $this->mailChimp->post('Member', $Member->toMailChimpArray());
            // Set MailChimp id on the Member and save Member into db
            $this->saveEntity($Member->setMailChimpId($response->get('id')));
        } catch (Exception $exception) {
            // Return error response if something goes wrong
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($Member->toArray());
    }

    /**
     * Remove MailChimp Member.
     *
     * @param string $MemberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(string $MemberId): JsonResponse
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpMember|null $Member */
        $Member = $this->entityManager->getRepository(MailChimpMember::class)->find($MemberId);

        if ($Member === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $MemberId)],
                404
            );
        }

        try {
            // Remove Member from database
            $this->removeEntity($Member);
            // Remove Member from MailChimp
            $this->mailChimp->delete(\sprintf('Member/%s', $Member->getMailChimpId()));
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse([]);
    }

    /**
     * Retrieve and return MailChimp Member.
     *
     * @param string $MemberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $MemberId): JsonResponse
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpMember|null $Member */
        $Member = $this->entityManager->getRepository(MailChimpMember::class)->find($MemberId);

        if ($Member === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $MemberId)],
                404
            );
        }

        return $this->successfulResponse($Member->toArray());
    }

    /**
     * Update MailChimp Member.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $MemberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $MemberId): JsonResponse
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpMember|null $Member */
        $Member = $this->entityManager->getRepository(MailChimpMember::class)->find($MemberId);

        if ($Member === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $MemberId)],
                404
            );
        }

        // Update Member properties
        $Member->fill($request->all());

        // Validate entity
        $validator = $this->getValidationFactory()->make($Member->toMailChimpArray(), $Member->getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                'message' => 'Invalid data given',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            // Update Member into database
            $this->saveEntity($Member);
            // Update Member into MailChimp
            $this->mailChimp->patch(\sprintf('Member/%s', $Member->getMailChimpId()), $Member->toMailChimpArray());
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($Member->toArray());
    }
}
