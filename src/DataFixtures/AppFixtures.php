<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $group = new Group();
        $group->setName('Test Group');
        $manager->persist($group);

        $categories = [];
        foreach (['Food', 'Transport', 'Entertainment', 'Health', 'Bills'] as $catName) {
            $category = new Category();
            $category->setName($catName);
            $categories[] = $category;
            $manager->persist($category);
        }

        $users = [];
        foreach ([
            ['email' => 'admin@test.com', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'user1@test.com', 'roles' => ['ROLE_USER']],
            ['email' => 'user2@test.com', 'roles' => ['ROLE_USER']],
        ] as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);
            $user->setGroup($group);
            $hashedPassword = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $users[] = $user;
            $manager->persist($user);
        }

        for ($i = 0; $i < 20; $i++) {
            $expense = new Expense();
            $expense->setAmount(mt_rand(-3000, 3000) / 100.0);
            $expense->setDescription('Test expense #' . ($i + 1));
            $expense->setDate(new \DateTime(sprintf('-%d days', mt_rand(0, 60))));
            $expense->setUser($users[array_rand($users)]);
            $expense->setCategory($categories[array_rand($categories)]);
            $manager->persist($expense);
        }

        $manager->flush();
    }
}
