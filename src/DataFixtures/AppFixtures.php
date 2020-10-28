<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Fixture reference repository
     *
     * @var ReferenceRepository
     */
    protected $referenceRepository;

    private $users = [
        ['Yasuo', 'dummy1@blog.com', 'kitten', ['ROLE_ADMIN']],
        ['BeXuanMai', 'dummy2@blog.com', 'kitten', ['ROLE_USER']],
        ['NguoiSoMot', 'dummy3@blog.com', 'kitten', ['ROLE_USER']],
    ];

    private $postTitle = 'Urna nisl sollicitudin id varius orci quam id turpis';

    private $posts = [
        ['Cách Quăng Lốc','Yasuo','Blog dạy cách quăng lốc ezgame 20GG'],
        ['1 + 1','NguoiSoMot','Blog tập đếm cùng NguoiSoMot'],
        ['Con Chim Non','BeXuanMai','Ôn lại một thời vàng son cùng con chim non'],
        ['Con Bướm Xinh','BeXuanMai', '20 bài hát hot nhất đầu 20s'],
        ['Hasagi','Yasuo','Blog dạy gáy to hơn gà trống đầu ngõ'],
        ['Ba ngọn nến lung linh','BeXuanMai','Bí mật gìn giữ hạnh phúc gia đình'],
    ];

    private $postContent = <<<'MARKDOWN'
Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
deserunt mollit anim id est laborum.

  * Ut enim ad minim veniam
  * Quis nostrud exercitation *ullamco laboris*
  * Nisi ut aliquip ex ea commodo consequat

Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
luctus dolor.

Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
nulla vitae est.

Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
Sed in egestas erat.

Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
tincidunt, faucibus nisl in, aliquet libero.
MARKDOWN;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadPosts($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->users as [$name, $email, $password, $roles]) {
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference('user_' . $name, $user);
        }
        $manager->flush();
    }

    private function loadPosts(ObjectManager $manager)
    {
        foreach ($this->posts as [$title, $author, $summary]) {
            $post = new Post();
            $post->setTitle($title);
            $post->setContent($this->postContent);
            $post->setPublishedAt(new \DateTime('now'));
            $post->setAuthor($this->getReference('user_' . $author));
            $post->setSummary($summary);

            $manager->persist($post);
            $this->addReference('post_' . $title, $post);
        }
        $manager->flush();
    }
}
