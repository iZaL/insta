<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateInstagram;
use App\Src\Instagram\InstagramRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Vinkla\Instagram\InstagramManager;

class InstagramsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */
    /**
     * @var InstagramRepository
     */
    protected $instagramRepository;

    protected $instagramManager;

    /**
     * Create a new controller instance.
     *
     * @param InstagramRepository $instagramRepository
     * @param InstagramManager $instagram
     */
    public function __construct(InstagramRepository $instagramRepository, InstagramManager $instagram)
    {
        $this->middleware('auth');
        $this->instagramRepository = $instagramRepository;
        $this->instagramManager = $instagram;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $instagrams = $this->instagramRepository->model->all();
        $likes = [];
        foreach ($instagrams as $instagram) {
            if ($instagram->access_token) {
                $this->setAccessToken($instagram->access_token);
            }
        }

        return view('instagrams.index')->with([
            'instagrams' => $instagrams,
            'likes' => $likes
        ]);
    }

    public function show($id)
    {
        $instagram = $this->instagramRepository->model->find($id);
        if (!$instagram->access_token) {
            //@todo : show un authenticated version;
            return Redirect::back()->withErrors('Please Authorize First');
        }
        $this->instagramManager->setAccessToken($instagram->access_token);
        $instagramUser = $this->instagramManager->getUser();
        $comments = $this->instagramManager->getMediaComments($instagram->access_token);
        $medias = $this->instagramManager->getUserMedia($instagram->client_id, 10);
        $likes = $this->instagramManager->getUserLikes(1000);
        $feeds = $this->instagramManager->getUserFeed(10);
        $follows = $this->instagramManager->getUserFollows();
        $followers = $this->instagramManager->getUserFollower();

        return view('instagrams.view',
            compact('instagram', 'instagramUser', 'likes', 'feeds', 'follows', 'followers', 'medias', 'comments'));
    }

    public function create()
    {
        return view('instagrams.create');
    }

    public function store(CreateInstagram $request)
    {
        // find the username
        $foundUser = $this->instagramRepository->model->where('username', $request->username)->first();
        if ($foundUser) {
            return Redirect::back()->withErrors('User already exists');
        }

        $response = $this->instagramManager->searchUser($request->username);

        // If bad request
        if (!isset($response->data[0])) {
            return Redirect::back()->withErrors('Sorry, Unkown User');
        }
        $user = $response->data[0];
        $instagram = $this->instagramRepository->model->create($request->all());
        $instagram->client_id = $user->id;
        $instagram->fullname = $user->full_name;
        $instagram->save();

        return Redirect::home()->withSuccess('Instagram Account Added');
    }

    public function authorize()
    {
        $loginUrl =
            $this->instagramManager->getLoginUrl([
                'basic',
                'likes',
                'relationships',
                'comments'

            ]);
        header('Location: ' . $loginUrl);
        die();
    }

    public function authenticate(Request $request)
    {
        $code = $request->get('code');

        if (!isset($code) || empty($code)) {
            // redirect
            dd('invalid access');
        }

        $data = $this->instagramManager->getOAuthToken($code);

        if (!$data->access_token) {
            return Redirect::action('InstagramsController@index')->withErrors('Wrong Access');

        }
        $instagram = $this->instagramRepository->model->where('client_id', $data->user->id)->first();

        if (!$instagram) {
            // redirect
            dd('wrong user');
        }

        $instagram->access_token = $data->access_token;

        $instagram->save();

        return Redirect::action('InstagramsController@index')->with('success', 'Authorized');
    }

    public function getUserInfoByUsername($username)
    {
        dd($this->instagramManager->searchUser($username));
    }

    public function getUserInfoByID($id)
    {
//        1097866395
        dd($this->instagramManager->getUserMedia('1097866395'));
    }

    public function dislikeMedia($username, $mediaID, Request $request)
    {
        $user = $this->instagramRepository->getByUsername($username);
        if (!$user->access_token) {
            throw new \Exception('invalid access token');
        }
        $this->setAccessToken($user->access_token);
        $this->instagramManager->deleteLikedMedia($mediaID);

        if ($request->ajax()) {
            return 'success';
        }

        return Redirect::back();
    }

    public function likeMedia($username, $mediaID, Request $request)
    {
        $user = $this->instagramRepository->getByUsername($username);
        if (!$user->access_token) {
            throw new \Exception('invalid access token');
        }
        $this->setAccessToken($user->access_token);
        $this->instagramManager->likeMedia($mediaID);

        if ($request->ajax()) {
            return 'success';
        }

        return Redirect::back();
    }

    public function setAccessToken($token)
    {
        if (empty($token)) {
            throw new \Exception('invalid token');
        }

        return $this->instagramManager->setAccessToken($token);
    }

    public function deleteAccessToken()
    {
        return $this->instagramManager->setAccessToken('');
    }

    public function loadMore()
    {
        $accounts = [];
        $response = [];
        $instagrams = $this->instagramRepository->model->all();

        foreach ($instagrams as $instagram) {
            if ($instagram->access_token) {
                $this->setAccessToken($instagram->access_token);
                $accounts[$instagram->username] = $this->instagramManager->pagination($this->instagramManager->getUserLikes(),20);
            }
        }

        foreach ($accounts as $username => $account) {
            if (!empty($account->pagination)) {
                $response[$username]['pagination'] = $account->pagination->next_max_like_id;
                $response[$username]['username'] = $username;
                foreach ($account->data as $data) {
                    $response[$username]['images'][] = [
                        'id' => $data->id,
                        'url' => $data->images->thumbnail->url,
                        'user' => $data->user->username
                    ];
                }
            }
        }

        return $response;
    }

    public function loadLike(Request $request)
    {
        $response = [];
        $username = $request->get('username');
        $instagram = $this->instagramRepository->getByUsername($username);
        $response['username'] = $username;

        if (trim(!(empty($request->pagination)) || !(is_null($request->pagination)) || !(isset($request->pagination)) || !($request->pagination == ''))) {
            if ($instagram->access_token) {
                $url = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' . $instagram->access_token;
                $url .= '&max_like_id=' . $request->get('pagination');
                $url .= '&count=20';
                $data = file_get_contents($url);
                $account = json_decode($data);
            }
            $response['pagination'] = isset($account->pagination->next_max_like_id) ? $account->pagination->next_max_like_id : null;
            foreach ($account->data as $data) {
                $response['images'][] = [
                    'id' => $data->id,
                    'url' => $data->images->thumbnail->url,
                    'user' => $data->user->username
                ];
            }
        } else {
            $response['image'] = null;
            $response['pagination'] = null;
        }

        return $response;
    }

}
