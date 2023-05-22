<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


class ApiClient
{
    private $client;
    private $serializer;

    public function __construct(HttpClientInterface $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getUsers(): array
    {
        return $this->client
            ->request('GET', '/api/user')
            ->toArray();
    }

    public function deleteUser($userId)
    {
        return $this->client
            ->request('DELETE', sprintf('/api/user/%d', $userId));
    }

    public function getProjectMilestones($projectId): array
    {
        return $this->client
            ->request('GET', sprintf('/api/project/%d/milestones', $projectId))
            ->toArray();
    }

    public function createProject($requestData)
    {

        $requestJson = $this->serializer->serialize($requestData, JsonEncoder::FORMAT, [
            'groups' => ['project'],
        ]);

        return $this->client
            ->request('POST', '/api/project', [
                'body' => $requestJson
            ]);
    }

    public function createMilestone($projectId, $requestData)
    {

        $requestJson = $this->serializer->serialize($requestData, JsonEncoder::FORMAT, [
            'groups' => ['milestone'],
        ]);

        return $this->client
            ->request('POST', sprintf('/api/project/%d/milestones', $projectId), [
                'body' => $requestJson
            ]);
    }


}