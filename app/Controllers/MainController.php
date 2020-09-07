<?php


namespace App\Controllers;


use Core\App;
use Core\Controller;
use App\Models\Ad;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function create(Request $request)
    {
        $data = $this->validate([
            'text' => ['notBlank',],
            'price' => ['notBlank','positive'],
            'limit' => ['notBlank','positive'],
            'banner' => ['notBlank','image'],
        ]);

        /** @var UploadedFile $file */
        $file = $data['banner'];
        $fileName = Str::random() . '.' . $file->getClientOriginalExtension();
        $file->move( App::getInstance()['paths']['banner_path'], $fileName);

        $ad = new Ad([
            'text' => $data['text'],
            'price' => (float) $data['price'],
            'limit' => $data['limit'],
            'banner' => $fileName,
        ]);
        $ad->save();

        return ['success' => true, 'id' => $ad->id];
    }

    public function get(Request $request)
    {
        $query = Ad::query()
            ->whereRaw('amount < `limit`')
            ->orderBy('price', 'desc');

        $ad = $query->first();
        if ($ad) {
            $query->increment('amount');
        } else {
            throw new NotFoundHttpException();
        }

        return [
            'text' => $ad->text,
            'banner' => App::getInstance()['url'] . '/images/' . $ad->banner,
        ];
    }

    public function update()
    {
        $data = $this->validate([
            'id' => ['notBlank', 'positive'],
            'text' => ['type' => ['type' => 'string']],
            'price' => ['positive'],
            'limit' => ['positive'],
            'banner' => ['image'],
        ]);

        $ad = Ad::query()->where('id', $data['id'])->first();
        if (!$ad)
            throw new NotFoundHttpException('Not found!');
        unset($data['id']);
        $ad->update($data);

        return $ad->toArray();
    }
}