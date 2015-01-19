<?php


class PostController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function actionIndex($page = 1) {

        $page = (int) $page;
        $inputSearchQuery = filter_input(INPUT_GET, 'inputSearchQuery', FILTER_SANITIZE_MAGIC_QUOTES);

        try {

            $pagination = new PaginationModel();
            $pagination->build(2, PostModel::countAll(['searchQuery' => $inputSearchQuery]));
            $pagination->setCurrentPage($page);

            $posts = PostModel::findAll([
                'searchQuery' => $inputSearchQuery,
                'offsetStart' => $pagination->offsetStart,
                'perPage' => $pagination->perPage
            ]);

            $view = & $this->_view;

            if (UserModel::isLogged()) {
                $view->addScript(BASE_SCRIPTS . 'trash.js', View::SCRIPT_POS_BOTTOM);
            }

            $viewParams = [
                'headerText' => 'Lista postów',
                'pagination' => $pagination,
                'inputSearchQuery' => stripslashes($inputSearchQuery),
                'posts' => $posts
            ];

            $view->render('post/list', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    public function actionDetails($id) {
        $id = (int) $id;

        try {

            $post = PostModel::findOne($id);

            if (!$post) {
                throw new InvalidArgumentException('Nie ma takiej strony!');
            }

            $view = & $this->_view;
            $viewParams = [
                'headerText' => $post['title'],
                'post' => $post
            ];

            $view->render('post/details', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }



    }

    public function actionDelete($id) {
        $id = (int) $id;

        echo PostModel::deleteById($id);
    }

    public function actionEdit($id) {
        $id = (int) $id;

        try {

            $post = PostModel::findOne($id);

            if (!$post) {
                throw new InvalidArgumentException('Nie ma takiego postu.');
            }

            $this->appendEditorScripts();

            $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);
            $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

            $inputRemoveImage = filter_input(INPUT_POST, 'inputRemoveImage');
            $inputImage = $_FILES["inputImage"];

            $model = new PostModel();

            if (!empty($_POST)) {
                $model->setID($id);
                $model->setTitle($inputTitle);
                $model->setDescription($inputDescription);
                $model->setImage($inputImage);
                $model->setConvertedImageName($post['thumb']);

                if ($inputRemoveImage) {
                    $model->removeImageFlag = true;
                    $model::removeImage($post['thumb']);
                    $model->setImage([]);
                }

                if ($model->processForm($model::ACTION_UPDATE_POST)) {
                    header("Location: " . BASE_APP_FOLDER . '/post/edit/' . $id);
                    return;
                }
            } else {
                $model->setTitle($post['title']);
                $model->setDescription(htmlspecialchars_decode($post['description'], ENT_QUOTES));
            }

            $view = & $this->_view;

            $viewParams = [
                'headerText' => 'Edytuj post',
                'model' => $model,
                'post' => $post
            ];

            $view->render('post/edit', $viewParams);


        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    public function actionAdd() {

        try {

            $this->appendEditorScripts();

            $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);
            $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

            $inputImage = $_FILES["inputImage"];

            $model = new PostModel();

            if (!empty($_POST)) {
                $model->setTitle($inputTitle);
                $model->setDescription($inputDescription);
                $model->setImage($inputImage);

                if ($model->processForm($model::ACTION_NEW_POST)) {
                    header("Location: " . BASE_APP_FOLDER);
                    return;
                }
            }

            $view = & $this->_view;
            $viewParams = [
                'headerText' => 'Dodaj post',
                'model' => $model
            ];

            $view->render('post/add', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    private function appendEditorScripts () {
        $view = & $this->_view;
        $view->addScript(BASE_SCRIPTS . 'ckeditor/ckeditor.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/config.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/styles.js', View::SCRIPT_POS_HEAD);
    }


}