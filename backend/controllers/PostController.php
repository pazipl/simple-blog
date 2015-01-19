<?php

/**
 * Class PostController - Kontroler, obsługuje listowanie, dodawanie, edycję, wyszukiwanie, usuwanie postów.
 */
class PostController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Akcja obsługuje żądanie wyświetlenia listy dodanych postów.
     *
     * @param int $page Numer strony dla paginatora.
     * @throws
     */
    public function actionIndex($page = 1) {

        // Numer strony dla paginatora.
        $page = (int)$page;
        // Filtruję wartość z pola wyszukiwania - jeśli przyleciało.
        $inputSearchQuery = filter_input(INPUT_GET, 'inputSearchQuery', FILTER_SANITIZE_MAGIC_QUOTES);

        try {
            // Tworzę nową instancję paginatora, ponieważ wyniki będą stronicowane.
            $pagination = new PaginationModel();
            // Ustawiam numer strony.
            $pagination->setCurrentPage($page);
            // Konfiguruję paginator na podstawie danych z `PostModel`.
            $pagination->build(PostModel::POST_LIST_MAX, PostModel::countAll(['searchQuery' => $inputSearchQuery]));

            // Pobieram listę postów, na podstawie ustalonych parametrów.
            $posts = PostModel::findAll([
                'searchQuery' => $inputSearchQuery,
                'offsetStart' => $pagination->offsetStart,
                'perPage' => $pagination->perPage
            ]);

            // Tworzę referencję do widoku.
            $view = &$this->_view;

            // Jeśli użytkownik jest zalogowany, dodaję skrypt trash.js, bindujący akcję usuwania postu.
            if (UserModel::isLogged()) {
                $view->addScript(BASE_SCRIPTS . 'trash.js', View::SCRIPT_POS_BOTTOM);
            }

            // Parametry widoku.
            $viewParams = [
                'headerText' => 'Lista postów',
                'pagination' => $pagination,
                'inputSearchQuery' => stripslashes($inputSearchQuery),
                'posts' => $posts
            ];

            // Wyświetlam widok, przekazując parametry widoku.
            $view->render('post/list', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    /**
     * Akcja obsługuje żądanie wyświetlenia szczegółów postu.
     *
     * @param int $id Identyfikator postu.
     * @throws
     */
    public function actionDetails($id) {
        // Identyfikator postu.
        $id = (int) $id;

        try {
            // Wyszukuje post w bazie, na podstawie $id.
            $post = PostModel::findOne($id);

            if (!$post) {
                throw new InvalidArgumentException('Nie ma takiej strony!');
            }

            // Tworzę referencję do widoku.
            $view = &$this->_view;

            // Parametry widoku.
            $viewParams = [
                'headerText' => $post['title'],
                'post' => $post
            ];

            // Wyświetlam widok, przekazując parametry widoku.
            $view->render('post/details', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    /**
     * Akcja obsługuje żądanie usunięcia postu.
     *
     * @param $id Identyfikator postu.
     */
    public function actionDelete($id) {
        // Identyfikator postu.
        $id = (int)$id;

        try {
            // Rzucam wyjątek, jeśli użytkownik nie jest zalogowany.
            UserModel::assertAuth();

            echo PostModel::deleteById($id);

        } catch (Exception $e) {
            new ErrorController($e);
        }
    }

    /**
     * Akcja obsługuje żadanie aktualizacji postu.
     *
     * @param $id Identyfikator postu.
     */
    public function actionEdit($id) {
        // Identyfikator postu.
        $id = (int)$id;

        try {
            // Rzucam wyjątek, jeśli użytkownik nie jest zalogowany.
            UserModel::assertAuth();

            // Wyszukuje post w bazie, na podstawie $id.
            $post = PostModel::findOne($id);

            if (!$post) {
                throw new InvalidArgumentException('Nie ma takiego postu.');
            }

            // Dołączam skrypty edytora WYSWIG.
            $this->appendEditorScripts();

            // Filtruje pole tytuł.
            $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);

            // Filtruję pole opisu.
            $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

            // Filtruję pole 'usuń zdjęcie'.
            $inputRemoveImage = filter_input(INPUT_POST, 'inputRemoveImage');

            // Pole z obrazkiem.
            $inputImage = $_FILES["inputImage"];

            // Tworzę instancję PostModel.
            $model = new PostModel();

            // Obsługuję formularz, jeśli przyleciały dane.
            if (!empty($_POST)) {

                // Przekazuję dane.

                $model->setID($id);
                $model->setTitle($inputTitle);
                $model->setDescription($inputDescription);
                $model->setImage($inputImage);
                $model->setConvertedImageName($post['thumb']);

                // Jeśli przyleciało pole 'usuń zdjęcie', obsługuję zdarzenie,
                if ($inputRemoveImage) {
                    $model->removeImageFlag = true;
                    $model::removeImage($post['thumb']);
                    $model->setImage([]);
                }

                // Jeśli formularz nie zawiera błędów i udało się zapisać dane do bazy.
                if ($model->processForm($model::ACTION_UPDATE_POST)) {
                    // Przekierowuje spowrotem na edycję postu.
                    header("Location: " . BASE_APP_FOLDER . '/post/edit/' . $id);
                    return;
                }
            } else {
                $model->setTitle($post['title']);
                $model->setDescription(htmlspecialchars_decode($post['description'], ENT_QUOTES));
            }

            // Tworzę referencję do instancji z widokiem.
            $view = &$this->_view;

            // Parametry widoku.
            $viewParams = [
                'headerText' => 'Edytuj post',
                'model' => $model,
                'post' => $post
            ];

            // Wyświetlam widok, przekazując parametry widoku.
            $view->render('post/edit', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    /**
     * Akcja obsługuje żadanie dodania nowego postu.
     */
    public function actionAdd() {

        try {
            // Rzucam wyjątek, jeśli użytkownik nie jest zalogowany.
            UserModel::assertAuth();

            // Dołączam skrypty edytora WYSWIG.
            $this->appendEditorScripts();

            // Filtruje pole tytuł.
            $inputTitle = filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING);

            // Filtruję pole opisu.
            $inputDescription = htmlspecialchars(filter_input(INPUT_POST, 'inputDescription'), ENT_QUOTES);

            // Pole z obrazkiem.
            $inputImage = $_FILES["inputImage"];

            // Tworzę instancję PostModel.
            $model = new PostModel();

            // Obsługuję formularz, jeśli przyleciały dane.
            if (!empty($_POST)) {

                // Przekazuję dane.

                $model->setTitle($inputTitle);
                $model->setDescription($inputDescription);
                $model->setImage($inputImage);

                // Jeśli formularz nie zawiera błędów i udało się zapisać dane do bazy.
                if ($model->processForm($model::ACTION_NEW_POST)) {
                    // Przekierowuje na stronę główną (liste postów).
                    header("Location: " . BASE_APP_FOLDER);
                    return;
                }
            }
            // Tworzę referencję do instancji z widokiem.

            $view = &$this->_view;

            // Parametry widoku.
            $viewParams = [
                'headerText' => 'Dodaj post',
                'model' => $model
            ];

            // Wyświetlam widok, przekazując parametry widoku.
            $view->render('post/add', $viewParams);

        } catch (Exception $e) {
            new ErrorController($e);
        }

    }

    /**
     * Metoda buduje tablice skryptów, potrzebnych do uruchomienia edytora WYSWIG.
     */
    private function appendEditorScripts() {
        $view = &$this->_view;
        $view->addScript(BASE_SCRIPTS . 'ckeditor/ckeditor.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/config.js', View::SCRIPT_POS_HEAD);
        $view->addScript(BASE_SCRIPTS . 'ckeditor/styles.js', View::SCRIPT_POS_HEAD);
    }

}