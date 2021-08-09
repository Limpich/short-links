<?php

namespace App\Http\Controllers;

use App\Services\LinkService;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LinkController extends Controller
{
    /**
     * @return array
     */
    private static function createValidationRules(): array
    {
        return [
            'url' => [
                'required',
                'url',
            ],
        ];
    }

    /**
     * @return string[]
     */
    private static function createValidationErrors(): array
    {
        return [
            'url.required' => 'Поле необходимо заполнить',
            'url.url' => 'Введите корректную ссылку',
        ];
    }

    /**
     * @return array
     */
    private static function deleteValidationRules(): array
    {
        return [
            'private' => 'required|regex:/[0-9a-zA-Z]{32}/',
        ];
    }

    /**
     * @var LinkService
     */
    private $linkService;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @param LinkService $linkService
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(
        LinkService $linkService,
        UrlGenerator $urlGenerator
    ) {
        $this->linkService = $linkService;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return SymfonyResponse
     */
    public function index(): SymfonyResponse
    {
        return response()->view('index');
    }

    /**
     * @param string $short
     * @return SymfonyResponse
     */
    public function redirect(string $short): SymfonyResponse
    {
        $link = $this->linkService->getOneByShort($short);

        if (is_null($link)) {
            /** Короткая ссылка не найдена */
            return response('', 404);
        }

        $url = $link->getUrl();
        
        return Redirect::to($url);
    }

    /**
     * @param Request $request
     * @return SymfonyResponse
     * @throws ValidationException
     * @throws \Exception
     */
    public function createFromForm(Request $request): SymfonyResponse
    {
        $request->validate(self::createValidationRules(), self::createValidationErrors());

        $url = $request->get('url');
        try {
            $link = $this->linkService->createLink($url);
        } catch (\Exception $ex) {
            /** Какая-то ошибка в БД, логично отдать 500 */
            return response('', 500);
        }

        return response()->view(
            'index',
            [
                'result' => $this->urlGenerator->to($link->getShort()),
                'url' => $url,
            ]
        );
    }

    /**
     * @param Request $request
     * @return SymfonyResponse
     */
    public function createFromApi(Request $request): SymfonyResponse
    {
        $json = $request->json()->all();

        $validator = Validator::make($json, self::createValidationRules(), self::createValidationErrors());

        if ($validator->fails()) {
            $data = [];

            foreach ($validator->errors()->keys() as $key) {
                $data[$key] = $validator->errors()->get($key)[0];
            }

            return response($data, 400);
        }

        try {
            $link = $this->linkService->createLink($json['url']);
        } catch (\Exception $ex) {
            /** Какая-то ошибка в БД, логично отдать 500 */
            return response('', 500);
        }

        return response()
            ->json(
                [
                    'url' => $this->urlGenerator->to($link->getShort()),
                    'private' => $link->getPrivate(),
                ]
            );
    }

    /**
     * @param Request $request
     * @return SymfonyResponse
     */
    public function deleteFromApi(Request $request): SymfonyResponse
    {
        $json = $request->json()->all();

        $validator = Validator::make($json, self::deleteValidationRules());

        if ($validator->fails()) {
            $data = [];

            foreach ($validator->errors()->keys() as $key) {
                $data[$key] = $validator->errors()->get($key)[0];
            }

            return response($data, 400);
        }

        $link = $this->linkService->getOneByPrivate($json['private']);
        if (is_null($link)) {
            /** Короткая ссылка не найдена */
            return response('', 404);
        }

        $linkDeleted = $this->linkService->deleteLink($link);

        if (!$linkDeleted) {
            return response('', 500);;
        }

        return response()
            ->json(['result' => 'Короткая ссылка удалена']);
    }
}
