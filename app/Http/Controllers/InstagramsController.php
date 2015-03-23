<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreateInstagram;
use App\Src\Instagram\InstagramRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
    private $instagramRepository;

    /**
     * Create a new controller instance.
     *
     * @param InstagramRepository $instagramRepository
     */
    public function __construct(InstagramRepository $instagramRepository)
    {
        $this->middleware('auth');
        $this->instagramRepository = $instagramRepository;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $instagrams = $this->instagramRepository->model->all();
        $clientID = $_ENV['INSTA_CLIENT_ID'];
        $redirectURI = $_ENV['INSTA_REDIRECT_URI'];

        return view('instagrams.index')->with([
            'instagrams' => $instagrams,
            'clientID' => $clientID,
            'redirectURI' => $redirectURI
        ]);
    }

    public function show($id)
    {
        $instagram = $this->instagramRepository->model->find($id);
        $likedUrl = $_ENV['INSTA_URL'] . '/users/self/media/liked?access_token=' . $_ENV['INSTA_ACCESS_TOKEN'];
        $likedContents = json_decode(file_get_contents($likedUrl), true);
        $likes = $likedContents['data'];

        return view('instagrams.view', compact('instagram', 'likes'));
    }

    public function create()
    {
        return view('instagrams.create');
    }

    public function store(CreateInstagram $request)
    {
        $json = file_get_contents($_ENV['INSTA_URL'] . '/users/search?q=' . $request->username . '/&client_id=' . $_ENV['INSTA_CLIENT_ID']);

        $response = json_decode($json, true);

        // If not valid Response
        if (!$response['meta']['code'] === 200) {
            return Redirect::back()->withErrors('Sorry, Cannot Process the Request, Please Make Sure You have Provided Correct Instagram Username');
        }

        // If bad request
        if (!isset($response['data'][0])) {
            return Redirect::back()->withErrors('Sorry, Bad Request');
        }

        $data = $response['data'][0];

        $instagram = $this->instagramRepository->model->create($request->all());
        $instagram->client_id = $data['id'];
        $instagram->fullname = $data['full_name'];
        $instagram->save();

        return Redirect::home()->withSuccess('Instagram Account Added');
    }

    public function authenticate($id)
    {
        $instagram = $this->instagramRepository->model->findOrFail($id);
        $clientID = $_ENV['INSTA_CLIENT_ID'];
        $redirectURI = $_ENV['INSTA_REDIRECT_URI'];
        Session::put('CURRENT_INSTA_USER', $instagram->id);

        return view('instagrams.authenticate')->with([
            'instagram' => $instagram,
            'clientID' => $clientID,
            'redirectURI' => $redirectURI
        ]);
    }

    public function confirmAuthenticate(Request $request)
    {
        $id = Session::has('CURRENT_INSTA_USER') ? Session::get('CURRENT_INSTA_USER') : 1; //@todo : change 1 to null
        Session::forget('CURRENT_INSTA_USER');
        if (is_null($id)) {
            // redirect
            dd('wrong user');
        }
        $code = $request->get('code');

        if (!isset($code) || empty($code)) {
            // redirect
            dd('invalid access');
        }


        $instagram = $this->instagramRepository->model->findOrFail($id);
        $instagram->access_token = Input::get('access_token');
        dd(Input::all());
    }

}
