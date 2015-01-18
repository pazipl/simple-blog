<?php


class PostController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function actionIndex($page = 1) {

        $page = (int) $page;

        $pagination = new PaginationModel();
        $pagination->build(2, PostModel::countAll());
        $pagination->setCurrentPage($page);

        $posts = PostModel::findAll([
            'offsetStart' => $pagination->offsetStart,
            'perPage' => $pagination->perPage
        ]);

        $view = & $this->_view;

        if (UserModel::isLogged()) {
            $view->addScript(BASE_SCRIPTS . 'trash.js', View::SCRIPT_POS_BOTTOM);
        }

        $viewParams = [
            'headerText' => 'Lista postów',
            'posts' => $posts,
            'pagination' => $pagination
        ];

        $view->render('post/list', $viewParams);
    }

    public function actionDetails($id) {
        $id = (int) $id;
        $post = PostModel::findOne($id);

        $view = & $this->_view;
        $viewParams = [
            'headerText' => $post['title'],
            'post' => $post
        ];

        $view->render('post/details', $viewParams);
    }

    public function actionDelete($id) {
        $id = (int) $id;

        echo PostModel::deleteById($id);
    }

    public function actionEdit($id) {
        $id = (int) $id;
        $post = PostModel::findOne($id);

        $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);
        $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

        $inputRemoveImage = filter_input(INPUT_POST, 'inputRemoveImage');
        $inputImage = $_FILES["inputImage"];

        $model = new PostModel();

        if (!empty($_POST)) {
            $model->setTitle($inputTitle);
            $model->setDescription($inputDescription);
            $model->setImage($inputImage);

//            if ($inputRemoveImage) {
//                $model::removeImage($post['thumb']);
//                $model->setImage([]);
//            }
//
//            if ($model->processForm()) {
//                header("Location: " . BASE_APP_FOLDER . '/post/edit/' . $id);
//                return;
//            }
        } else {
            $model->setTitle($post['title']);
            $model->setDescription(htmlspecialchars_decode($post['description'], ENT_QUOTES));
        }

        $view = & $this->_view;
        $view->addScript(BASE_SCRIPTS . 'ckeditor/ckeditor.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/config.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/styles.js', View::SCRIPT_POS_HEAD);

        $viewParams = [
            'headerText' => 'Edytuj post',
            'model' => $model,
            'post' => $post
        ];

        $view->render('post/edit', $viewParams);
    }

    public function actionAdd() {

        $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);
        $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

        $inputImage = $_FILES["inputImage"];

        $model = new PostModel();

        if (!empty($_POST)) {
            $model->setTitle($inputTitle);
            $model->setDescription($inputDescription);
            $model->setImage($inputImage);

            if ($model->processForm()) {
                header("Location: " . BASE_APP_FOLDER);
                return;
            }
        }

        $view = & $this->_view;
        $view->addScript(BASE_SCRIPTS . 'ckeditor/ckeditor.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/config.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/styles.js', View::SCRIPT_POS_HEAD);

        $viewParams = [
            'headerText' => 'Dodaj post',
            'model' => $model
        ];

        $view->render('post/add', $viewParams);

    }


}