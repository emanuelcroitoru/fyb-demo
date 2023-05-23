<?php

namespace App\Controller\API;

use App\Entity\Project;
use App\Entity\ProjectMilestones;
use App\Entity\User;
use App\Repository\ProjectMilestonesRepository;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiUserController extends AbstractController
{

    #[Route('/api/user/{id}', name: 'api_user_delete', requirements: ['id' => '[\d]+'], options: ['expose' => true], methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json('No user found for id ' . $id, 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(sprintf('Successfully deleted user with id %d', $id));
    }

    #[Route('/api/user', name: 'api_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users =  $userRepository->getAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user['id'],
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName'],
                'email' => $user['email'],
                'enabled' => $user['enabled'],
                'status' => User::$statuses[$user['status']],
                'countProjects' => $user['projectsCount']
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/project/{id}', name: 'api_project_delete', requirements: ['id' => '[\d]+'], options: ['expose' => true], methods: ['DELETE'])]
    public function projectDelete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);

        if (!$project) {
            return $this->json('No project found for id ' . $id, 404);
        }

        $entityManager->remove($project);
        $entityManager->flush();

        return $this->json(sprintf('Successfully deleted user with id %d ', $id));
    }



    #[Route('/api/user/{id}/projects', name: 'api_user_projects', options: ['expose' => true], methods: ['GET'],)]
    public function projectsList(
        ManagerRegistry $doctrine,
        int $id,
        ProjectRepository $projectRepository,
    ): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json('No project found for id' . $id, 404);
        }

        $userProjects =  $projectRepository->findBy([
            'user' => $user
        ]);

        $data = [];
        foreach ($userProjects as $userProject) {
            $data[] = [
                'id' => $userProject->getId(),
                'title' => $userProject->getTitle(),
                'description' => $userProject->getDescription()
            ];
        }

        return $this->json($data);
    }

    #[Route('/api/project/{id}/milestones', name: 'api_project_milestone', options: ['expose' => true], methods: ['GET'],)]
    public function projectMilestones(
        ManagerRegistry $doctrine,
        int $id,
        ProjectMilestonesRepository $projectMilestonesRepository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);
        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }

        $projectData = [
            'id' => $project->getId(),
            'title' => $project->getTitle(),
            'description' => $project->getDescription(),
        ];

        $projectMilestones =  $projectMilestonesRepository->findBy([
            'project' => $project
        ]);

        $data['project'] = $projectData;
        $data['milestones'] = [];
        foreach ($projectMilestones as $milestone) {
            $data['milestones'][] = [
                'id' => $milestone->getId(),
                'title' => $milestone->getTitle(),
                'description' => $milestone->getDescription(),
                'milestoneDeadline' => $milestone->getMilestoneDeadline(),

            ];
        }

        return $this->json($data);
    }

    #[Route('/api/milestone/{id}', name: 'api_milestone_delete', requirements: ['id' => '[\d]+'], options: ['expose' => true], methods: ['DELETE'])]
    public function milestoneDelete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $milestone = $entityManager->getRepository(ProjectMilestones::class)->find($id);

        if (!$milestone) {
            return $this->json('No milestone found for id ' . $id, 404);
        }

        $entityManager->remove($milestone);
        $entityManager->flush();

        return $this->json(sprintf('Successfully deleted milestone with id %d ', $id));
    }

    #[Route('/api/project', name: 'api_project_add',  methods: ['POST'])]
    public function projectAdd(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager,): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $userId = $requestData['user']['id'];
        $user = $entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return $this->json('User not found.', 404);
        }

        $project = $serializer->deserialize(
                $request->getContent(),
                Project::class,
                'json',
                ['groups' => ['project']],
            );

        $project->setUser($user);
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json(sprintf('Successfully added project'));

    }

    #[Route('/api/project/{id}/milestones', name: 'api_milestone_add', requirements: ['id' => '[\d]+'], methods: ['POST'])]
    public function milestoneAdd(SerializerInterface $serializer, Request $request, EntityManagerInterface $entityManager,): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $projectId = $requestData['project']['id'];
        $project = $entityManager->getRepository(Project::class)->find($projectId);

        if (!$project) {
            return $this->json('Project not found.', 404);
        }

        $milestone = $serializer->deserialize(
            $request->getContent(),
            ProjectMilestones::class,
            'json',
            ['groups' => ['milestone']],
        );

        $milestone->setProject($project);
        $entityManager->persist($milestone);
        $entityManager->flush();

        return $this->json(sprintf('Successfully added a new milestone'));

    }



}
