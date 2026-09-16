<?php

namespace App\Controller;

use App\Entity\Habit;
use App\Entity\HabitLog;
use App\Form\HabitType;
use App\Repository\HabitLogRepository;
use App\Repository\HabitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(
        Request $request, 
        HabitRepository $habitRepository, 
        HabitLogRepository $habitLogRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_auth');
        }

        // Fetch habits linked directly to this user via the Many-to-Many relationship
        $habits = $user->getHabits()->toArray();
        $todayStr = (new \DateTime())->format('Y-m-d');

        // Handle Adding New Habits Form
        $habit = new Habit();
        $form = $this->createForm(HabitType::class, $habit);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $request->request->has('add_habit')) {
            $hasChanges = false;
            $submittedHabits = $request->request->all('habits');

            if (!empty($submittedHabits)) {
                foreach ($submittedHabits as $habitTitle) {
                    $habitTitle = trim($habitTitle);
                    if (!empty($habitTitle)) {
                        // Check if the habit template already exists globally
                        $existingHabit = $habitRepository->findOneBy(['title' => $habitTitle]);

                        if (!$existingHabit) {
                            // Create a new global habit template if it doesn't exist
                            $existingHabit = new Habit();
                            $existingHabit->setTitle($habitTitle);
                            $existingHabit->setCreatedAt(new \DateTimeImmutable());
                            $entityManager->persist($existingHabit);
                        }

                        // Attach it to the user if they haven't selected it yet
                        if (!$user->getHabits()->contains($existingHabit)) {
                            $user->addHabit($existingHabit);
                            $hasChanges = true;
                        }
                    }
                }
            }

            if ($hasChanges) {
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_dashboard');
        }

        // Handle Ticking Today's Habits
        if ($request->isMethod('POST') && $request->request->has('save_logs')) {
            $submittedHabitIds = $request->request->all('habit_ids');

            foreach ($habits as $h) {
                $isCompleted = in_array($h->getId(), $submittedHabitIds);
                $existingLog = $habitLogRepository->findOneBy([
                    'habit' => $h,
                    'completedDate' => new \DateTime($todayStr)
                ]);

                if ($isCompleted && !$existingLog) {
                    $log = new HabitLog();
                    $log->setHabit($h);
                    $log->setCompletedDate(new \DateTime($todayStr));
                    $entityManager->persist($log);
                } elseif (!$isCompleted && $existingLog) {
                    $entityManager->remove($existingLog);
                }
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_dashboard');
        }

        // Generate last 10 days array
        $last10Days = [];
        for ($i = 9; $i >= 0; $i--) {
            $date = new \DateTime("-$i days");
            $last10Days[] = [
                'date' => $date,
                'label' => $date->format('M d'),
                'key' => $date->format('Y-m-d')
            ];
        }

        // Fetch logs for these habits over the last 10 days
        $completedLogsMap = [];
        if (count($habits) > 0) {
            $startDate = new \DateTime('-9 days');
            $startDate->setTime(0, 0, 0);
            
            $habitIds = array_map(fn($h) => $h->getId(), $habits);
            $logs = $habitLogRepository->createQueryBuilder('hl')
                ->join('hl.habit', 'h')
                ->where('h.id IN (:habitIds)')
                ->andWhere('hl.completedDate >= :startDate')
                ->setParameter('habitIds', $habitIds)
                ->setParameter('startDate', $startDate)
                ->getQuery()
                ->getResult();

            foreach ($logs as $log) {
                $hId = $log->getHabit()->getId();
                $dKey = $log->getCompletedDate()->format('Y-m-d');
                $completedLogsMap[$hId][$dKey] = true;
            }
        }

        $todayCompletedIds = [];
        foreach ($habits as $h) {
            $log = $habitLogRepository->findOneBy([
                'habit' => $h,
                'completedDate' => new \DateTime($todayStr)
            ]);
            if ($log) {
                $todayCompletedIds[] = $h->getId();
            }
        }

        // Fetch all system default templates to show as clickable pills in the modal
        $defaultHabitsList = $habitRepository->findAll();

        return $this->render('dashboard/index.html.twig', [
            'habits' => $habits,
            'defaultHabits' => $defaultHabitsList,
            'habitForm' => $form->createView(),
            'last10Days' => $last10Days,
            'completedLogsMap' => $completedLogsMap,
            'todayCompletedIds' => $todayCompletedIds,
        ]);
    }
}