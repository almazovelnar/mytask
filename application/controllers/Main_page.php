<?php

use Model\{Boosterpack_model, Comment_model, Login_model, Post_model, User_model};

/**
 * Class Main_page
 */
class Main_page extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        $user = User_model::get_user();

        App::get_ci()->load->view('main_page', ['user' => User_model::preparation($user, 'default')]);
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id)
    { // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS
        $post_id = intval($post_id);

        if (empty($post_id))
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        try {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex) {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $posts =  Post_model::preparation($post, 'full_info');

        return $this->response_success(['post' => $posts]);
    }


    public function comment()
    { // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )

        if (!User_model::is_logged())
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        $data = json_decode(file_get_contents('php://input'));

        $postId = intval($data->id);
        $message = $data->commentText;
        $parentId = $data->parentId ?? null;

        if (empty($postId) || empty($message)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($postId);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $post->comment(User_model::get_user()->get_id(), $postId, $message, $parentId);

        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }


    public function login()
    {
        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->login) || empty($data->password))
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        if (($user = User_model::getByEmail($data->login)) !== null) {
            if (Login_model::validatePassword($user, $data->password)) {
                Login_model::start_session($user);
            } else {
                return $this->response_error('User not Found');
            }
        } else {
            return $this->response_error('User not Found');
        }

        return $this->response_success(['user' => $user->get_id()]);
    }


    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/', 'http'));
    }

    public function add_money(){
        if (!User_model::is_logged())
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        var_dump($this->input->post());die;


        $data = json_decode(file_get_contents('php://input'));

        $amount = doubleval($data->sum);

        $user = User_model::get_user();
        $balance = $user->addToBalance($amount);

        return $this->response_success(['amount' => $balance]);
    }

    public function buy_boosterpack(){
        if (!User_model::is_logged())
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        $user = User_model::get_user();
        $data = json_decode(file_get_contents('php://input'));
        $packId = intval($data->id);

        $pack = new Boosterpack_model($packId);

        if (($packPrice = $pack->get_price()) > $user->get_wallet_balance())
            return $this->response_error(CI_Core::RESPONSE_BALANCE_NOT_ENOUGH);

        $likes = rand(1, $packPrice + $pack->get_bank());
        $pack->addToBank($packPrice - $likes);


        return $this->response_success([
            'amount' => $likes,
            'balance' => $user->minusFromBalance($packPrice),
            'likes' => $user->addToLikes($likes),
        ]);
    }

    public function like(){
        if (!User_model::is_logged())
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        $like = 1;
        $user = User_model::get_user();
        if ($user->getLikes() - $like < 0)
            return $this->response_error(CI_Core::RESPONSE_LIKES_NOT_ENOUGH);

        $data = json_decode(file_get_contents('php://input'));
        $postId = intval($data->id);

        if (empty($postId))
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        try {
            $post = new Post_model($postId);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        if ($post->like($like))
            $likes = $user->minusFromLikes($like);

        $posts =  Post_model::preparation($post, 'full_info');

        return $this->response_success([
            'post' => $posts,
            'likes' => $likes ?? 0
        ]);
    }

    public function comment_like(){
        if (!User_model::is_logged())
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NEED_AUTH);

        $like = 1;
        $user = User_model::get_user();
        if ($user->getLikes() - $like < 0)
            return $this->response_error(CI_Core::RESPONSE_LIKES_NOT_ENOUGH);

        $data = json_decode(file_get_contents('php://input'));
        $commentId = intval($data->id);

        if (empty($commentId))
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);

        try {
            $comment = new Comment_model($commentId);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        if ($comment->like($like))
            $likes = $user->minusFromLikes($like);

        $posts =  Post_model::preparation(new Post_model($comment->get_assign_id()), 'full_info');

        return $this->response_success([
            'post' => $posts,
            'likes' => $likes ?? 0
        ]);
    }
}
