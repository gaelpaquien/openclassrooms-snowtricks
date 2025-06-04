<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604183820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add test data for demo';
    }

    public function up(Schema $schema): void
    {
        $hashedPassword = '$2y$13$FqWLOM19DYi7bypUp.t5SOxU0KggQnDLVQe3LlV6Nu8ss9S9Ie8B6';

        $this->addSql("INSERT INTO trick_category (name) VALUES 
            ('Grabs'), ('Rotations'), ('Flips'), ('Slides')");

        $this->addSql("INSERT INTO user (email, username, profile_picture, password, roles, is_verified) VALUES 
            ('admin@email.com', 'admin', 'default.webp', '{$hashedPassword}', '[\"ROLE_ADMIN\"]', 1)");

        $this->addSql("INSERT INTO user (email, username, profile_picture, password, roles, is_verified) VALUES 
            ('user1@email.com', 'user1', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user2@email.com', 'user2', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user3@email.com', 'user3', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user4@email.com', 'user4', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user5@email.com', 'user5', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user6@email.com', 'user6', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user7@email.com', 'user7', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user8@email.com', 'user8', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user9@email.com', 'user9', 'default.webp', '{$hashedPassword}', '[]', 1),
            ('user10@email.com', 'user10', 'default.webp', '{$hashedPassword}', '[]', 1)");

        $this->addSql("INSERT INTO trick (title, slug, description, category_id, author_id, created_at) VALUES 
            ('Stalefish', 'stalefish', 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »', 1, 1, NOW()),
            ('Tail grab', 'tail-grab', 'Saisie de la partie arrière de la planche, avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).', 1, 2, NOW()),
            ('Truck driver', 'truck-driver', 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture). Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).', 1, 3, NOW()),
            ('Indy', 'indy', 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »', 1, 4, NOW()),
            ('Mute', 'mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »', 1, 5, NOW()),
            ('Nose grab', 'nose-grab', 'Saisie de la partie avant de la planche, avec la main avant. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).', 1, 6, NOW()),
            ('360 ou trois six', '360-ou-trois-six', 'Un tour complet, soit 360 degrés. On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre.', 2, 7, NOW()),
            ('1080 ou big foot', '1080-ou-big-foot', 'Trois tours complet, soit 1080 degrés. On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre.', 2, 8, NOW()),
            ('Front flip', 'front-flip', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation. Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées. Néanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks..', 3, 9, NOW()),
            ('Back flip', 'back-flip', 'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation. Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées. Néanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks..', 3, 10, NOW())");

        for ($i = 1; $i <= 35; $i++) {
            $categoryId = rand(1, 4);
            $authorId = rand(1, 10);
            $this->addSql("INSERT INTO trick (title, slug, description, category_id, author_id, created_at) VALUES 
                ('Trick de test n°{$i}', 'trick-de-test-n-{$i}', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', {$categoryId}, {$authorId}, NOW())");
        }

        for ($i = 1; $i <= 45; $i++) {
            $randInt = rand(1, 6);
            $this->addSql("INSERT INTO trick_image (name, trick_id) VALUES ('TrickFixtures-{$randInt}.webp', {$i})");
        }

        for ($i = 1; $i <= 45; $i++) {
            $this->addSql("INSERT INTO trick_video (url, trick_id) VALUES ('https://www.youtube.com/embed/aINlzgrOovI', {$i})");
        }

        $comments = [
            'Super trick !',
            'Magnifique figure',
            'J\\\'aimerais bien réussir ça',
            'Impressionnant !',
            'Technique parfaite',
            'Wow, incroyable !',
            'Ça donne envie de s\\\'y mettre',
            'Belle démonstration',
            'Du grand art !',
            'Respect pour cette performance'
        ];

        for ($i = 0; $i < 100; $i++) {
            $trickId = rand(1, 45);
            $authorId = rand(1, 10);
            $comment = $comments[array_rand($comments)];
            $this->addSql("INSERT INTO comment (content, author_id, trick_id, created_at) VALUES ('{$comment}', {$authorId}, {$trickId}, NOW())");
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM comment');
        $this->addSql('DELETE FROM trick_video');
        $this->addSql('DELETE FROM trick_image');
        $this->addSql('DELETE FROM trick');
        $this->addSql('DELETE FROM user');
        $this->addSql('DELETE FROM trick_category');
    }
}
