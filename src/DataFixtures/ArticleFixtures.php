<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $point = $this->generateRandomPoint();
            $article = new Article();
            $article->setTitle($this->randomContent($i, true).' '.$i);
            $article->setContent($this->randomContent($i));
            $article->setLatitude($point[0]);
            $article->setLongitude($point[1]);
            if ($i < 4) {
                $article->setCategory($this->getReference(CategoryFixtures::CAT_REFERENCE_0));
            } elseif ($i > 16) {
                $article->setCategory($this->getReference(CategoryFixtures::CAT_REFERENCE_4));
            } elseif ($i >= 4 && $i < 9) {
                $article->setCategory($this->getReference(CategoryFixtures::CAT_REFERENCE_1));
            } elseif ($i >= 9 && $i < 14) {
                $article->setCategory($this->getReference(CategoryFixtures::CAT_REFERENCE_2));
            } else {
                $article->setCategory($this->getReference(CategoryFixtures::CAT_REFERENCE_3));
            }
            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }

    private function randomContent($key, $first_word = false)
    {
        $lorem = [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean placerat tortor purus, non malesuada quam dapibus laoreet. Vestibulum sed malesuada nunc. Aenean condimentum vehicula laoreet. Pellentesque sit amet sagittis erat. Praesent nunc ipsum, volutpat at ipsum et, bibendum condimentum ante. Etiam quis laoreet massa. Mauris ut pellentesque nisl, sit amet maximus ante. Quisque eget nisi id nisl accumsan pretium ut vel nisi. Pellentesque et ligula neque. Integer ac sapien eu dolor faucibus posuere eget quis nibh. Integer nec volutpat ligula, congue posuere odio. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nunc et placerat erat, vulputate suscipit elit. Maecenas sed nisl et eros porttitor consectetur.',
            'Curabitur nec diam pellentesque libero pellentesque tincidunt. Sed suscipit gravida interdum. Vestibulum ut purus non lorem tincidunt sollicitudin. Donec vitae leo tellus. Aliquam consectetur mi eu enim interdum suscipit. Morbi interdum quam eu quam mattis tempus. Suspendisse vulputate mattis dolor et dignissim. Mauris dui libero, tempus at porta eget, pellentesque sed justo. Duis rhoncus, felis ut porttitor suscipit, eros sem rhoncus massa, vel pretium ligula velit at urna. Proin eget venenatis mi. Sed sed faucibus lectus, vel placerat erat. Nam eleifend pulvinar elementum.',
            'Sed elementum quam in orci lobortis tristique. Curabitur dapibus laoreet turpis, eu condimentum ex eleifend nec. Fusce vehicula egestas eros quis dictum. Mauris hendrerit faucibus lacus. Maecenas dignissim odio et efficitur posuere. Nulla non odio ut urna venenatis sollicitudin. Mauris sodales dolor ac nulla efficitur, vitae venenatis ligula tempus. Sed purus justo, aliquam sed nisl eget, eleifend facilisis nisi.',
            'Duis eu felis dolor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum nulla risus, condimentum consequat arcu a, gravida facilisis dui. Sed feugiat eleifend luctus. Proin tristique iaculis pulvinar. Etiam auctor, augue non semper dictum, nibh ante feugiat purus, non lacinia arcu elit a erat. Mauris vitae erat a ligula interdum posuere nec eu tellus.',
            'Morbi velit felis, elementum vitae nibh vitae, iaculis faucibus nulla. Quisque a erat vel elit maximus facilisis nec in sapien. Phasellus auctor ut risus id viverra. Praesent tempus quam sit amet magna facilisis eleifend. Nullam et hendrerit tortor. Donec vel lectus malesuada, ornare velit ut, blandit leo. Donec quis condimentum purus, ac mollis erat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec lacinia enim in nunc ullamcorper commodo. Pellentesque at orci in purus consequat malesuada.',
            'Mauris ullamcorper vehicula enim pretium mattis. Praesent vel sagittis urna. Maecenas non vulputate nisi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget tortor in ante faucibus euismod maximus lobortis nibh. Nullam mattis malesuada tellus, at dignissim quam consectetur ornare. Mauris in orci non ipsum egestas viverra. Ut cursus maximus sem, vitae accumsan magna egestas efficitur.',
            'Nulla tempor enim nec mi tincidunt, vitae dignissim dolor mollis. Suspendisse eget lacus aliquam, dignissim sapien eu, finibus justo. Donec ac lorem suscipit, consectetur est tincidunt, venenatis risus. Ut facilisis arcu facilisis, tincidunt augue ut, porta elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce et sapien tempor, tempor diam a, consectetur sem. Integer tempor elementum arcu, ac finibus ipsum fringilla ac. Etiam eu vestibulum arcu, quis placerat velit. Nullam fermentum eros ac quam aliquam ultrices. Curabitur sagittis, ligula nec congue feugiat, nisl nisl placerat ligula, eget viverra elit ex quis augue. Nam sit amet nisl eu tortor ultricies placerat.',
            'Sed vulputate elit ac odio cursus lobortis. Maecenas molestie turpis ut leo molestie vestibulum. Sed vel mauris vel purus vulputate porta. In hac habitasse platea dictumst. Suspendisse potenti. Nunc volutpat justo sit amet viverra ornare. Nam non volutpat orci. Nunc luctus diam orci, eu varius augue finibus ac. Nullam dictum egestas diam rutrum elementum. Nulla a ornare turpis.',
            'Nam in cursus leo. Nam scelerisque justo eu semper sollicitudin. Ut vestibulum magna ac eros hendrerit dictum. Duis dapibus enim erat, eu semper ipsum iaculis sed. Sed sagittis volutpat augue, nec sodales sapien laoreet sed. Fusce in metus bibendum, sollicitudin ex a, molestie sem. Ut a vestibulum velit. Nunc tristique, lacus nec efficitur placerat, risus mauris mollis dui, vel semper ex est quis neque. Vivamus porta mauris tellus, ac hendrerit nibh ornare dignissim. Mauris ut augue orci. Pellentesque imperdiet velit justo, eget congue libero facilisis id. Praesent ultricies luctus justo, ac scelerisque orci convallis sit amet. Nunc elementum, tortor et volutpat porta, lacus tortor aliquet enim, id consectetur turpis nibh id massa. Sed venenatis ante ac nulla consectetur, at facilisis magna aliquet. Ut malesuada semper tortor sed sagittis. Morbi pharetra ex dolor, id viverra enim scelerisque quis.',
            'Quisque porttitor elementum consequat. Proin velit mi, dictum semper magna a, lacinia ultricies sem. Phasellus mattis rutrum urna ut ornare. Suspendisse ornare malesuada est ut accumsan. Maecenas ac ante vel ipsum pulvinar tincidunt. Curabitur vehicula id nulla vitae ultrices. Duis semper arcu arcu, vel venenatis odio tristique vel. Pellentesque nec venenatis velit. Donec egestas ex a erat luctus, vestibulum finibus massa venenatis. Vestibulum vehicula metus vitae purus cursus, luctus sodales tortor blandit. Curabitur lobortis nunc quam, convallis tempus purus elementum id. Nunc egestas nisl ac vestibulum imperdiet. Phasellus tincidunt lorem est, ut cursus sapien dignissim ut.',
            'Fusce mollis convallis turpis, eu vestibulum lorem accumsan ut. Aliquam arcu massa, tempor in justo vel, dictum fringilla ex. Quisque ornare facilisis ex, nec pulvinar ex vulputate sed. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Curabitur nec enim enim. Praesent consectetur dignissim accumsan. Maecenas pharetra sollicitudin lectus, ut sollicitudin ipsum molestie vitae. Mauris quis vestibulum odio.',
            'Sed cursus erat non felis mattis tincidunt. Phasellus sollicitudin vel dolor placerat suscipit. Vestibulum gravida hendrerit fringilla. In purus dolor, lobortis vitae consectetur eget, molestie at velit. Curabitur id nulla elementum, vestibulum orci a, efficitur eros. Integer nunc nulla, malesuada ut ipsum vel, euismod hendrerit diam. Nullam ullamcorper, purus vitae vulputate bibendum, sem nisi dictum nunc, nec sollicitudin tortor risus sit amet massa. Morbi a nibh bibendum, placerat lectus a, laoreet ante. In gravida sapien id lectus egestas, ac rutrum erat aliquet. Quisque tellus neque, porta id est eu, elementum tempus mauris. Vivamus euismod velit eleifend lorem aliquet, eu laoreet lorem tristique.',
            'Nam ac pharetra mi. Ut sapien mi, gravida id mauris ut, fermentum consequat neque. Donec imperdiet metus eros, sit amet hendrerit leo elementum sed. Etiam ultrices mi eget dui lobortis suscipit. Mauris cursus, justo id dignissim sodales, mauris velit efficitur ipsum, eget mollis justo justo non risus. Fusce a turpis facilisis, viverra est et, consectetur odio. In vestibulum diam sapien, a faucibus odio pretium non. Donec eget massa quis quam laoreet accumsan placerat pellentesque ipsum. Pellentesque commodo mattis massa, et vulputate sapien. Etiam commodo quam ut commodo sollicitudin. Nunc nec urna rutrum, pretium leo id, lobortis ante. Vestibulum varius fringilla augue, at mollis turpis sodales a. Etiam risus ante, lobortis ac quam imperdiet, aliquet suscipit justo. In eu leo sem.',
            'Nulla facilisi. Donec in gravida tortor, ut mattis ipsum. Morbi non dui quis lorem fringilla congue vitae sed magna. Nunc eros quam, congue eu erat vitae, tristique porta ante. Morbi suscipit volutpat ultricies. Nunc auctor turpis non orci bibendum volutpat. Vivamus eget ligula a quam condimentum tempus. Nullam eros sapien, laoreet tincidunt nibh vel, venenatis finibus ante. In dignissim justo eget nisi euismod posuere. Nam bibendum purus mi, in aliquet nisi tincidunt eget. In id sem purus. Vivamus id scelerisque lectus. Proin dictum arcu eu dictum euismod.',
            'Donec sed dignissim arcu, vitae dapibus tortor. Pellentesque et nisl ut sapien rutrum vestibulum. Aliquam consectetur vestibulum dolor, quis ullamcorper mauris egestas sit amet. Vestibulum in enim vitae enim semper auctor eu malesuada metus. Aenean dictum turpis eget dui suscipit commodo. Duis condimentum ante non odio ultricies elementum. Aenean semper dictum lobortis. Pellentesque commodo risus eget finibus semper. Nulla laoreet sapien non sem semper pharetra. Mauris in dolor commodo, tincidunt mi a, eleifend metus. Vestibulum ac sem luctus orci aliquet mollis nec in eros.',
            'Etiam at tortor ligula. Fusce nunc augue, tristique sit amet purus pellentesque, dignissim iaculis turpis. Praesent a scelerisque lorem, in placerat nulla. Aenean at nunc vel est facilisis finibus. Nullam rutrum justo eget gravida hendrerit. Vivamus eu iaculis nulla, eu lobortis felis. Fusce fermentum neque sem, non faucibus massa feugiat vitae. Phasellus vitae elementum neque, at porttitor nulla. Etiam vel iaculis nulla, in sodales ante. Cras quis facilisis enim, eget tincidunt mi.',
            'Quisque eleifend purus eu vestibulum dignissim. In hac habitasse platea dictumst. Morbi massa arcu, rhoncus in feugiat vel, tincidunt id leo. Morbi eget nunc a mi eleifend feugiat. Sed imperdiet, elit id finibus malesuada, nibh mauris porttitor erat, sed pharetra risus lorem eu mi. Integer ut urna pulvinar, sagittis ante non, molestie nisi. Mauris felis augue, sodales eget varius nec, placerat vitae elit.',
            'Vestibulum luctus turpis at lectus tempor ornare. Vestibulum sit amet sapien posuere, lacinia ipsum a, feugiat quam. Pellentesque ipsum nulla, venenatis vel ultricies in, faucibus non eros. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vivamus quis velit sit amet diam porta dictum eu ac nibh. Curabitur condimentum, turpis vitae pretium euismod, elit lorem euismod lorem, nec tincidunt ligula erat vitae neque. Suspendisse potenti. Ut fermentum mi non sem varius hendrerit. Vestibulum elit metus, interdum non molestie nec, euismod ut ex. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Fusce scelerisque urna eget ipsum ultrices rhoncus. Mauris sodales purus tempor nunc ornare, sit amet pellentesque sem vehicula. Sed a mauris ipsum.',
            'Quisque a elit erat. Nulla porta, felis quis tincidunt malesuada, ipsum nibh interdum lacus, quis sodales lacus orci vitae diam. Curabitur laoreet et magna quis congue. Curabitur eget velit ornare, malesuada nulla eu, tincidunt est. Ut sed enim non lorem faucibus mattis ac id mi. Nam tellus lorem, blandit sed elit quis, dictum ultrices mi. Maecenas mattis augue eget massa pretium blandit. Nulla non nisl volutpat, sagittis tellus eu, maximus urna.',
            'Cras eu massa quis orci condimentum elementum eget vel lacus. Donec commodo urna diam, nec imperdiet neque egestas non. Integer quis quam mauris. Vestibulum elementum, nisl volutpat aliquam hendrerit, nulla ex posuere tellus, quis varius velit dolor id dui. Nullam sed risus non neque tincidunt gravida sit amet vitae lorem. Proin commodo leo justo, non accumsan ante placerat eu. Vivamus rutrum molestie urna, id luctus justo varius vitae. Maecenas elementum faucibus nisi, eget consectetur leo sollicitudin quis. Maecenas eu scelerisque lacus.',
        ];

        if ($first_word) {
            $arr = explode(' ',trim($lorem[$key]));
            return $arr[0];
        }

        return $lorem[$key];
    }

    private function generateRandomPoint()
    {
        $precision = 6;
        $bound = [
            [-90, -180],// LAT MIN, LNG MIN
            [90, 180],// LAT MAX, LNG MAX
        ];
        $lat = round($bound[0][0] + (lcg_value() * abs($bound[1][0] - $bound[0][0])), $precision);
        $lng = round($bound[0][1] + (lcg_value() * abs($bound[1][1] - $bound[0][1])), $precision);

        return [$lat, $lng];
    }

}