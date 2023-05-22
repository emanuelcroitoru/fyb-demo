<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\ProjectMilestones;
use App\Form\ProjectMilestonesType;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\ApiClient;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class UserController extends AbstractController
{

    #[Route('/', name: 'app_users', methods: ['GET', 'POST'])]
    public function index(Request $request, ApiClient $apiClient): Response
    {
        $users = $apiClient->getUsers();

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiClient->createProject($project);
            return $this->redirectToRoute('app_users');

        }

        return $this->render('user/list.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);
    }

    #[Route('/project/{id}/milestones', name: 'app_project_milestones', options: ['expose' => true], methods: ['GET', 'POST'])]
    public function projectMilestones(
        Request $request,
        ApiClient $apiClient,
        ProjectRepository $projectRepository,
        int $id): Response
    {
        $response = $apiClient->getProjectMilestones($id);
        $project = $projectRepository->find($id);
        if (!$project) {
            throw $this->createNotFoundException(
                'No old report found for id '. $id
            );
        }
        $milestone = new ProjectMilestones();
        $milestone->setProject($project);
        $form = $this->createForm(ProjectMilestonesType::class, $milestone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiClient->createMilestone($id, $milestone);
            return $this->redirectToRoute('app_project_milestones', ['id' => $project->getId()]);

        }


        return $this->render('project/milestones.html.twig', [
            'data' => $response,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_delete', methods: ['GET'])]
    public function deleteMilestone(ApiClient $apiClient, int $id): Response
    {
        $apiClient->deleteUser($id);
        return $this->redirectToRoute('app_users');

    }


}
