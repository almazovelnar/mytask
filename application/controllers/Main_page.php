<?php

use Model\{Login_model, Post_model, User_model};

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

        if (empty($postId) || empty($message)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $post = new Post_model($postId);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $comment = $post->comment(User_model::get_user()->get_id(), $postId, $message);

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
        // todo: 4th task  add money to user logic
        return $this->response_success(['amount' => rand(1,55)]); // Кол-во лайков под постом \ комментарием чтобы обновить . Сейчас рандомная заглушка
    }

    public function buy_boosterpack(){
        // todo: 5th task add money to user logic
        return $this->response_success(['amount' => rand(1,55)]); // Кол-во лайков под постом \ комментарием чтобы обновить . Сейчас рандомная заглушка
    }


    public function like(){
        // todo: 3rd task add like post\comment logic
        return $this->response_success(['likes' => rand(1,55)]); // Кол-во лайков под постом \ комментарием чтобы обновить . Сейчас рандомная заглушка
    }

}
