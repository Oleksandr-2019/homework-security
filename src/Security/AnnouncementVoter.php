<?php
// src/Security/AnnouncementVoter.php
namespace App\Security;

use App\Entity\Announcement;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AnnouncementVoter extends Voter
{
    //ці рядки тільки вигадані: ви можете використовувати що завгодно
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        //якщо атрибут не один, який ми підтримуємо, поверніть false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        //голосуйте лише за об'єкти розміщення всередині цього voter
        if (!$subject instanceof Announcement) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            //користувач повинен увійти; якщо ні, забороняйте доступ
            return false;
        }

        //ви знаєте, що $subject - це об'єкт Announcement, завдяки підтримкам
        /** @var Announcement $announcement */
        $announcement = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($announcement, $user);
            case self::EDIT:
                return $this->canEdit($announcement, $user);
        }

        throw new \LogicException('Цей код не можна досягти!');
    }

    private function canView(Announcement $announcement, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($announcement, $user)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return !$announcement->isPrivate();
    }

    private function canEdit(Announcement $announcement, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $announcement->getUser()->getId() === $user->getId();
    }
}