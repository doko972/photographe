<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Photo;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private array $vikingNames = [
        ['pseudo' => 'Björn le Valeureux',      'name' => 'Bjorn Eriksen'],
        ['pseudo' => 'Sigrid la Chasseresse',   'name' => 'Sigrid Olsen'],
        ['pseudo' => 'Ragnar Croc-de-fer',       'name' => 'Ragnar Larsen'],
        ['pseudo' => 'Astrid la Farouche',       'name' => 'Astrid Hansen'],
        ['pseudo' => 'Gunnar l\'Implacable',     'name' => 'Gunnar Magnusson'],
        ['pseudo' => 'Freya aux Yeux d\'Ambre',  'name' => 'Freya Thorsdottir'],
        ['pseudo' => 'Harald Tempête-du-Nord',   'name' => 'Harald Odinsson'],
        ['pseudo' => 'Ingrid Dent-de-Loup',      'name' => 'Ingrid Svensson'],
    ];

    private array $captions = [
        'Prêt à conquérir le buffet !',
        'Ma hache est plus grande que la tienne.',
        'Valhalla ou pas, c\'est la fête ce soir !',
        'Costume fait main, malgré les moqueries.',
        'Florent m\'a défié. Florent a perdu.',
        'Aurélie, ton anniversaire est légendaire !',
        '40 ans pour eux, une nuit épique pour nous.',
        'Je suis venu, j\'ai bu, j\'ai conquis.',
        'Le Valhalla attend, mais pas avant minuit.',
        'Viking de cœur, comptable le lundi.',
        null,
        null,
    ];

    public function run(): void
    {
        Storage::disk('public')->makeDirectory('photos/originals');
        Storage::disk('public')->makeDirectory('photos/thumbnails');

        // Admin
        $admin = User::create([
            'name'          => 'Admin',
            'email'         => 'admin@kenaurelie.fr',
            'password'      => Hash::make('admin1234'),
            'viking_pseudo' => 'Le Grand Jarl',
            'is_admin'      => true,
        ]);

        // Regular users
        $users = [];
        foreach ($this->vikingNames as $i => $data) {
            $users[] = User::create([
                'name'          => $data['name'],
                'email'         => 'user' . ($i + 1) . '@kenaurelie.fr',
                'password'      => Hash::make('password'),
                'viking_pseudo' => $data['pseudo'],
                'is_admin'      => false,
            ]);
        }

        // Photos
        $approvedPhotos = [];
        foreach ($users as $idx => $user) {
            $photoCount = rand(2, 4);
            for ($p = 0; $p < $photoCount; $p++) {
                $hue = ($idx * 45 + $p * 20) % 360;
                [$origPath, $thumbPath] = $this->makePlaceholder($hue);

                $caption = $this->captions[array_rand($this->captions)];
                $status  = ($p === 0 && $idx % 3 === 0) ? 'pending' : 'approved';

                $photo = Photo::create([
                    'user_id'        => $user->id,
                    'original_path'  => $origPath,
                    'thumbnail_path' => $thumbPath,
                    'viking_pseudo'  => $user->viking_pseudo,
                    'caption'        => $caption,
                    'status'         => $status,
                    'created_at'     => now()->subDays(rand(0, 14))->subHours(rand(0, 23)),
                ]);

                if ($status === 'approved') {
                    $approvedPhotos[] = $photo;
                }
            }
        }

        // Likes: each user likes 40–80 % of approved photos
        foreach ($users as $user) {
            $tolike = collect($approvedPhotos)
                ->filter(fn ($p) => $p->user_id !== $user->id)
                ->shuffle()
                ->take((int) (count($approvedPhotos) * (rand(40, 80) / 100)));

            foreach ($tolike as $photo) {
                Like::create(['user_id' => $user->id, 'photo_id' => $photo->id]);
            }
        }

        // Votes: first 5 users rank their top liked photos
        foreach (array_slice($users, 0, 5) as $user) {
            $liked = Like::where('user_id', $user->id)
                ->with('photo')
                ->get()
                ->pluck('photo')
                ->filter(fn ($p) => $p && $p->status === 'approved')
                ->shuffle()
                ->take(10);

            foreach ($liked->values() as $rank => $photo) {
                Vote::create([
                    'user_id'  => $user->id,
                    'photo_id' => $photo->id,
                    'rank'     => $rank + 1,
                ]);
            }
        }
    }

    private function makePlaceholder(int $hue): array
    {
        $filename = uniqid('demo_', true);

        [$r, $g, $b] = $this->hslToRgb($hue, 0.45, 0.30);

        // Original 800×600
        $orig = imagecreatetruecolor(800, 600);
        $bg   = imagecolorallocate($orig, $r, $g, $b);
        imagefill($orig, 0, 0, $bg);
        $text = imagecolorallocate($orig, 220, 190, 120);
        imagestring($orig, 5, 340, 290, 'Viking', $text);

        $origPath = 'photos/originals/' . $filename . '.jpg';
        ob_start();
        imagejpeg($orig, null, 85);
        $origData = ob_get_clean();
        imagedestroy($orig);
        Storage::disk('public')->put($origPath, $origData);

        // Thumbnail 600×450
        [$tr, $tg, $tb] = $this->hslToRgb($hue, 0.5, 0.35);
        $thumb = imagecreatetruecolor(600, 450);
        $tbg   = imagecolorallocate($thumb, $tr, $tg, $tb);
        imagefill($thumb, 0, 0, $tbg);
        $ttext = imagecolorallocate($thumb, 220, 190, 120);
        imagestring($thumb, 4, 263, 218, 'Viking', $ttext);

        $thumbPath = 'photos/thumbnails/' . $filename . '_thumb.jpg';
        ob_start();
        imagejpeg($thumb, null, 70);
        $thumbData = ob_get_clean();
        imagedestroy($thumb);
        Storage::disk('public')->put($thumbPath, $thumbData);

        return [$origPath, $thumbPath];
    }

    private function hslToRgb(int $h, float $s, float $l): array
    {
        $h /= 360;
        $q  = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p  = 2 * $l - $q;

        $r = (int) round($this->hueToRgb($p, $q, $h + 1 / 3) * 255);
        $g = (int) round($this->hueToRgb($p, $q, $h)         * 255);
        $b = (int) round($this->hueToRgb($p, $q, $h - 1 / 3) * 255);

        return [$r, $g, $b];
    }

    private function hueToRgb(float $p, float $q, float $t): float
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1 / 6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1 / 2) return $q;
        if ($t < 2 / 3) return $p + ($q - $p) * (2 / 3 - $t) * 6;
        return $p;
    }
}
