<?php


namespace App\Voter;


use App\Entity\Task;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /**
         * @var Task
         */
        $task = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($task, $user);
            case self::DELETE:
                return $this->canDelete($task, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canEdit(Task $task, User $user)
    {
        return $user === $task->getUser();
    }

    private function canDelete(Task $task, User $user)
    {
        if ((is_null($task->getUser()) && $this->security->isGranted('ROLE_ADMIN')) || $user === $task->getUser()) {
            return true;
        }
        return false;
    }
}